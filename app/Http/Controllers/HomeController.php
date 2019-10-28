<?php

namespace App\Http\Controllers;

use App\Box;
use App\Level;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.home');
        }
        $boxes = Box::all();
        $user = auth()->user();
        $summaryCollection = new Collection();
        foreach ($boxes as $box) {
            $flagsFound = 0;
            $points = 0;
            foreach ($box->levels as $level) {
                if ($user->submissions()->where('level_id', $level->id)->first()) {
                    $flagsFound++;
                    $points += $level->points;
                }
            }
            $summaryCollection->push([
                'box' => $box,
                'completePercentage' => $box->levels->count() > 0 ? ($flagsFound / $box->levels->count() * 100) : 0,
                'flagsFoundText' => $flagsFound . ' / ' . $box->levels->count() . ' flags found.',
                'points' => $points
            ]);
        }
        return view('home', [
            'summary' => $summaryCollection,
            'feed' => auth()->user()->submissions()->orderBy('created_at', 'DESC')->limit(10)->get()
        ]);
    }

    public function submitFlag(Request $request) {

        $validator = Validator::make($request->all(), [
            'flag' => 'required|string|max:255|min:4'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $flag = strtolower($request->get('flag'));
        $user = auth()->user();
        foreach (Level::all() as $level) {
            if (Hash::check($flag, $level->flag)) {
                if ($user->submissions()->where('level_id', $level->id)->count() > 0) {
                    toastr()->error('Flag already submitted!', 'Duplicate Flag Submission');
                } else {
                    $submission = new Submission();
                    $submission->user_id = $user->id;
                    $submission->box_id = $level->box->id;
                    $submission->level_id = $level->id;
                    $submission->submitted_text = $request->get('flag');
                    $submission->saveOrFail();
                    toastr()->success('Flag no. ' . $level->flag_no . ' submitted for ' . $level->box->title . ' box.', 'Valid Flag Submission');
                }
                return back();
            }
        }
        toastr()->error('Invalid Flag Submitted!', 'Invalid Flag');
        return back();
    }

}
