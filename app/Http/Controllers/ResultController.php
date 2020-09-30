<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ResultController extends Controller
{
    //
    public function failed_running(Request $request)
    {
        $uuid = $request->input('uuid');
        $user_id = Jobs::where('uuid', $uuid)->value('user_id');
        $user_name = User::where('id', $user_id)->value('name');
        $file_path = $user_name . '/' . $uuid . '/.nextflow.log';
        $nextflowLog = Storage::get($file_path);
        return view('RunResult.failedRunning', ['nextflowLog' => $nextflowLog]);
    }

    public function success_running(Request $request)
    {
        $sample_id = $request->input('sampleID');
        $started = Jobs::where('sample_id', $sample_id)->value('started');
        $finished = Jobs::where('sample_id', $sample_id)->value('finished');
        $period = $finished - $started;
        $command = Jobs::where('sample_id', $sample_id)->value('command');
        return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command]);
    }
}
