<?php

namespace App\Http\Controllers;

use App\Box;
use App\Level;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AdminController extends Controller
{


    public function index() {
        $counts = [
            'boxes' => Box::all()->count(),
            'flags' => Level::all()->count(),
            'teams' => User::where('role', 'USER')->count()
        ];
        return view('admin.index', [
            'counts' => $counts,
            'boxes' => Box::all(),
            'teams' => User::where('role', 'USER')->get()
        ]);
    }

    public function storeBox(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'string|min:2|required',
            'description' => 'string|min:2',
            'difficulty' => 'numeric',
            'logo' => 'file|mimes:jpeg,bmp,png',
            'author' => 'string|min:2',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $newBox = new Box();
        if ($request->hasFile('logo')) {
            try {
                $logo = $request->file('logo');
                $filename = str_replace('-', '', Uuid::uuid4()->toString()) . '-' . $logo->getFilename() . '.' . $logo->getClientOriginalExtension();
                $logo->storeAs('public/boxes/', $filename);
                $newBox->fill(['logo' => $filename]);
            } catch (\Exception $e) {
                toastr()->warning($e->getMessage(), 'File Error');
            }
        }
        $newBox->fill($request->except('logo'));
        $newBox->save();
        toastr()->success('Box saved successfully!');
        return back();
    }

    public function storeTeam(Request $request) {
        $validator = Validator::make($request->all(), [
            'display_name' => 'string|min:2|required',
            'username' => 'required|string|min:4',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'avatar' => 'file|mimes:jpeg,bmp,png',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                toastr()->error($err);
            }
            return back();
        }

        $newTeam = new User();
        $newTeam->fill($request->except('password'));
        if ($request->hasFile('avatar')) {
            try {
                $logo = $request->file('avatar');
                $filename = str_replace('-', '', Uuid::uuid4()->toString()) . '-' . $logo->getFilename() . '.' . $logo->getClientOriginalExtension();
                $logo->storeAs('public/avatars/', $filename);
                $newTeam->fill(['avatar' => $filename]);
            } catch (\Exception $e) {
                toastr()->warning($e->getMessage(), 'File Error');
            }
        }
        $newTeam->fill(['password' => Hash::make($request->get('password'))]);
        $newTeam->save();
        toastr()->success('Team saved successfully!');
        return back();
    }

    public function showBox($id) {
        $box = Box::findOrFail($id);
        $teams = User::where('role', 'USER')->get();
        $levels = $box->levels;
        $boxProgress = new Collection();
        $boxLevelCount = $box->levels->count() > 0 ? $box->levels->count() : 1;

        foreach ($teams as $team) {
            $flagsFound = 0;
            $points = 0;
            foreach ($levels as $level) {
                if ($team->submissions()->where('level_id', $level->id)->count() > 0) {
                    $flagsFound++;
                    $points += $level->points;
                }
            }
            $boxProgress->push([
                'team' => $team->display_name,
                'team_id' => $team->id,
                'progress' => $flagsFound / $boxLevelCount * 100,
                'points' => $points
            ]);
        }
        return view('admin.box', [
            'box' => $box,
            'flags' => $box->levels,
            'boxProgress' => $boxProgress
        ]);
    }

    public function storeFlag(Request $request) {
        $validator = Validator::make($request->all(), [
            'box_id' => 'required|numeric',
            'flag' => 'required',
            'points' => 'required|numeric|gte:1'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                toastr()->error($err);
            }
            return back();
        }
        $lastLevel = 0;
        $box = Box::findOrFail($request->get('box_id'));
        if ($box->levels->count() > 0) {
            $lastLevel = $box->levels()->orderBy('flag_no', 'DESC')->first()->flag_no;
        }
        $newLevel = new Level();
        $newLevel->fill([
            'box_id' => $request->get('box_id'),
            'flag_no' => $lastLevel + 1,
            'flag' => $request->get('flag'),
            'points' => $request->get('points'),
        ]);
        $newLevel->save();
        toastr()->success('Level ' . $newLevel->flag_no . ' added successfully!');
        return back();
    }

    public function deleteFlag(Request $request) {
        if ($request->has('level_id')) {
            $level = Level::findOrFail($request->get('level_id'));
            $flagNo = $level->flag_no;
            $box = $level->box;
            $otherLevels = $box->levels()->where('flag_no', '>', $flagNo)->orderBy('flag_no', 'ASC')->get();
            foreach ($otherLevels as $lvl) {
                $lvl->flag_no = $lvl->flag_no - 1;
                $lvl->save();
            }
            $level->delete();
            toastr()->success('Flag Deleted!');
            return response()->json('ok');
        }
        return response()->json('fail', 422);
    }

    public function deleteBox($id) {
        $box = Box::findOrFail($id);
        $box->delete();
        return response()->json('ok');
    }

    public function showTeam($id) {
        $team = User::findOrFail($id);
        $boxes = Box::all();
        $feed = $team->submissions()->orderBy('created_at', 'DESC')->get();
        $progress = new Collection();
        foreach ($boxes as $box){
            $levels = $box->levels;
            $boxLevelCount = $box->levels->count() > 0 ? $box->levels->count() : 1;
            $flagsFound = 0;
            $points = 0;
            foreach ($levels as $level){
                if($team->submissions()->where('level_id',$level->id)->count() > 0){
                    $flagsFound++;
                    $points += $level->points;
                }
            }
            $progress->push([
                'box' => $box->title,
                'progress' => $flagsFound / $boxLevelCount * 100,
                'score' => $points
            ]);
        }

        return view('admin.team', [
            'team' => $team,
            'progress' => $progress,
            'feed' => $feed
        ]);
    }

    public function summary(){
        $teams = User::where('role', 'USER')->orderBy('created_at','DESC')->get();
        $points = new Collection();
        foreach ($teams as $team){
            $teamPoints = 0;
            foreach ($team->submissions as $submission){
                $teamPoints+= $submission->level->points;
            }
            $points->push(['team' => $team->display_name, 'points' => $teamPoints]);
        }
        return view('admin.summary',[
            'points' => $points
        ]);
    }

}
