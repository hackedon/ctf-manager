<?php

namespace App\Http\Controllers;

use App\Box;
use App\Config;
use App\HintRequest;
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

        $allowReportUploads = Config::where('key', 'allowReportUploads')->first()->value === '1';
        $allowFlagSubmission = Config::where('key', 'allowFlagSubmission')->first()->value === '1';

        return view('admin.index', [
            'counts' => $counts,
            'boxes' => Box::all(),
            'teams' => User::where('role', 'USER')->get(),
            'allowReportUploads' => $allowReportUploads,
            'allowFlagSubmission' => $allowFlagSubmission
        ]);
    }

    public function storeBox(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'string|min:2|required',
            'description' => 'string|min:2|required',
            'difficulty' => 'numeric|gte:1|lte:10',
            'logo' => 'file|mimes:jpeg,bmp,png',
            'author' => 'string|min:2|nullable',
            'url' => 'string|url|nullable'
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
            'affiliation' => 'string|min:2',
            'username' => 'required|string|min:4|unique:users',
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
                $filename = str_replace('-', '', Uuid::uuid4()->toString()) . '-' . $logo->getClientOriginalName() . '.' . $logo->getClientOriginalExtension();
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
            'flag' => 'required|string|min:4|max:255',
            'points' => 'required|numeric|gte:1'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                toastr()->error($err);
            }
            return back();
        }

        foreach (Level::all() as $lvl) {
            if (Hash::check(strtolower($request->get('flag')), $lvl->flag)) {
                toastr()->error('Exception in validating flag. Make sure the flag is unique.');
                return back();
            }
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
            'flag' => Hash::make(strtolower($request->get('flag'))),
            'points' => $request->get('points'),
        ]);
        try {
            $newLevel->save();
        } catch (\Exception $exception) {
            toastr()->error('Exception in saving flag. Make sure the flag is unique.');
            return back();
        }
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
        if ($box->submissions->count() > 0) {
            $box->submissions()->delete();
        }
        if ($box->levels->count() > 0) {
            $box->levels()->delete();
        }
        if ($box->hints->count() > 0) {
            $box->hints()->delete();
        }
        $box->delete();
        toastr()->success('Box deleted successfully!');
        return response()->json('ok');
    }

    public function showTeam($id) {
        $team = User::findOrFail($id);
        if($team->isAdmin()){
            toastr()->error('Invalid Team');
            return back();
        }
        $boxes = Box::all();
        $feed = $team->submissions()->orderBy('created_at', 'DESC')->get();
        $progress = new Collection();
        foreach ($boxes as $box) {
            $levels = $box->levels;
            $boxLevelCount = $box->levels->count() > 0 ? $box->levels->count() : 1;
            $flagsFound = 0;
            $points = 0;
            foreach ($levels as $level) {
                if ($team->submissions()->where('level_id', $level->id)->count() > 0) {
                    $flagsFound++;
                    $points += $level->points;
                }
            }
            $progress->push([
                'box' => $box->title,
                'progress' => $flagsFound / $boxLevelCount * 100,
                'flagFraction' => $flagsFound . ' / ' . $boxLevelCount,
                'score' => $points
            ]);
        }

        return view('admin.team', [
            'team' => $team,
            'progress' => $progress,
            'feed' => $feed,
            'reports' => $team->reports()->orderBy('created_at', 'DESC')->get(),
            'hintRequests' => $team->hints
        ]);
    }

    public function deleteTeam($id) {
        $team = User::findOrFail($id);
        if ($team->isAdmin()) {
            return response()->json('invalid user', 422);
        }
        if ($team->submissions->count() > 0) {
            $team->submissions()->delete();
        }
        if ($team->reports->count() > 0) {
            $team->reports()->delete();
        }
        if ($team->hints->count() > 0) {
            $team->hints()->delete();
        }
        $team->delete();
        toastr()->success('Team deleted successfully!');
        return response()->json('ok');
    }

    public function summary() {
        $teams = User::where('role', 'USER')->orderBy('created_at', 'DESC')->get();
        $points = new Collection();
        foreach ($teams as $team) {
            $teamPoints = 0;
            foreach ($team->submissions as $submission) {
                $teamPoints += $submission->level->points;
            }
            $points->push(['team' => $team->display_name, 'points' => $teamPoints]);
        }
        return view('admin.summary', [
            'points' => $points
        ]);
    }

    public function saveSettings(Request $request) {
        if ($request->has('allowFlagSubmission')) {
            $val = $request->get('allowFlagSubmission') === '1' ? '1' : '0';
            $config = Config::where('key', 'allowFlagSubmission')->first();
            $config->value = $val;
            $config->save();
            toastr()->success('Allow Flag Submission updated');
        } elseif ($request->has('allowReportUploads')) {
            $val = $request->get('allowReportUploads') === '1' ? '1' : '0';
            $config = Config::where('key', 'allowReportUploads')->first();
            $config->value = $val;
            $config->save();
            toastr()->success('Allow Report Uploads updated');
        } else {
            toastr()->error('Invalid Request');
        }
        return back();
    }

    public function showHintRequests() {
        return view('admin.hint_requests', [
            'hintRequests' => HintRequest::paginate(20)
        ]);
    }

    public function toggleActiveStatus(Request $request) {
        if ($request->has('value') && $request->has('request_id')) {
            $rq = HintRequest::findOrFail($request->get('request_id'));
            $rq->active = $request->get('value') === '1' ? false : true;
            $rq->save();
            return response()->json(['status' => 'ok'], 200);
        }
        return response()->json(['status' => 'fail'], 422);
    }

    public function updateCost(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|numeric',
            'cost' => 'required|numeric|gte:0|lte:10'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail'], 422);
        }

        $rq = HintRequest::findOrFail($request->get('request_id'));
        $rq->cost = $request->get('cost');
        $rq->save();
        return response()->json(['status' => 'ok'], 200);

    }
}
