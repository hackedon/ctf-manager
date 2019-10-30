<?php

namespace App\Http\Controllers;

use App\Box;
use App\Config;
use App\HintRequest;
use App\Level;
use App\Report;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.home');
        }
        $user = auth()->user();
        $key = $user->username . '_data';
        if (Cache::has($key)) {
            $data = Cache::get($key);
            return view('home', [
                'summary' => $data['summary'],
                'feed' => $data['feed'],
                'canUpload' => $data['canUpload'],
                'remainingUploads' => $data['remainingUploads'],
                'allowReportUploads' => $data['allowReportUploads'],
                'allowFlagSubmission' => $data['allowFlagSubmission'],
            ]);
        }

        $boxes = Box::all();
        $summaryCollection = new Collection();
        foreach ($boxes as $box) {
            $flagsFound = 0;
            $points = 0;
            $totalPoints = 0;
            foreach ($box->levels as $level) {
                $totalPoints += $level->points;
                if ($user->submissions()->where('level_id', $level->id)->first()) {
                    $flagsFound++;
                    $points += $level->points;
                }
            }
            $summaryCollection->push([
                'box' => $box,
                'completePercentage' => $box->levels->count() > 0 ? ($flagsFound / $box->levels->count() * 100) : 0,
                'flagsFoundText' => $flagsFound . ' / ' . $box->levels->count() . ' flags found',
                'points' => $points,
                'totalPoints' => $totalPoints
            ]);
        }

        $allowReportUploads = Config::where('key', 'allowReportUploads')->first()->value === '1';
        $allowFlagSubmission = Config::where('key', 'allowFlagSubmission')->first()->value === '1';
        $feed = auth()->user()->submissions()->orderBy('created_at', 'DESC')->limit(10)->get();

        Cache::put($key, [
            'summary' => $summaryCollection,
            'feed' => $feed,
            'canUpload' => $user->reports->count() < 5,
            'remainingUploads' => 5 - $user->reports->count(),
            'allowReportUploads' => $allowReportUploads,
            'allowFlagSubmission' => $allowFlagSubmission
        ], now()->addMinutes(5));

        return view('home', [
            'summary' => $summaryCollection,
            'feed' => $feed,
            'canUpload' => $user->reports->count() < 5,
            'remainingUploads' => 5 - $user->reports->count(),
            'allowReportUploads' => $allowReportUploads,
            'allowFlagSubmission' => $allowFlagSubmission
        ]);
    }

    public function submitFlag(Request $request) {
        $allowFlagSubmission = Config::where('key', 'allowFlagSubmission')->first()->value === '1';
        if (!$allowFlagSubmission) {
            toastr()->error('Flag Submission Halted!');
            return back();
        }
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
                    $key = $user->username . '_data';
                    Cache::forget($key);
                    toastr()->success('Flag no. ' . $level->flag_no . ' submitted for ' . $level->box->title . ' box.', 'Valid Flag Submission');
                }
                return back();
            }
        }
        toastr()->error('Invalid Flag Submitted!', 'Invalid Flag');
        return back();
    }

    public function uploadReport(Request $request) {
        $allowReportUploads = Config::where('key', 'allowReportUploads')->first()->value === '1';
        if (!$allowReportUploads) {
            toastr()->error('Report Uploads Halted!');
            return back();
        }
        if (auth()->user()->reports->count() >= 5) {
            toastr()->error('You have reached your upload limit. Only 5 uploads are allowed.', 'Upload Limit Reached');
            return back();
        }
        if ($request->hasFile('report')) {
            $file = $request->file('report');
            $ext = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            if (($mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $mime === 'application/msword') && ($ext === 'docx' || $ext === 'doc')) {
                $filename = str_replace('-', '', Uuid::uuid4()->toString()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/reports/', $filename);
                $report = new Report();
                $report->fill([
                    'user_id' => auth()->user()->id,
                    'original_filename' => $file->getClientOriginalName(),
                    'submission' => $filename
                ]);
                $report->save();
                toastr()->success('Report uploaded successfully!');
                $remaining = 5 - Report::where('user_id', auth()->user()->id)->count();
                toastr()->info('You have ' . $remaining . ' upload chances remaining.');
                $key = auth()->user()->username . '_data';
                Cache::forget($key);
                return back();
            } else {
                toastr()->error('Allowed formats are .docx and .doc');
                return back();
            }
        }
        toastr()->error('File not found in Request!');
        return back();
    }

    public function handleRequestHint(Request $request) {
        if ($request->has('box_id')) {
            $box = Box::findOrFail($request->get('box_id'));
            $hintRequest = new HintRequest();
            $hintRequest->fill([
                'user_id' => auth()->user()->id,
                'box_id' => $box->id
            ]);
            if ($hintRequest->save()) {
                return response()->json(['status' => 'ok'], 201);
            }
            return response()->json(['status' => 'fail'], 500);
        }
        return response()->json(['status' => 'fail'], 422);
    }

}
