<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
        $sample_user = User::where('id', $sample_id)->value('name');
        $sample_uuid = Jobs::where('sample_id', $sample_id);
        $started = Jobs::where('sample_id', $sample_id)->value('started');
        $finished = Jobs::where('sample_id', $sample_id)->value('finished');
        $period = $finished - $started;
        $command = Jobs::where('sample_id', $sample_id)->value('command');
        return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'sample_id' => $sample_id, 'sample_user' => $sample_user, 'sample_uuid' => $sample_uuid]);
    }

    public function download_result(Request $request)
    {
        $sample_id = $request->input('sampleID');
        $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
        $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
        $sample_username = User::where('id', $user_id)->value('name');
        $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        $result_path  = $sample_username . '/' . $uuid . '/results';
        $zip_name = $sample_username . '/' . $uuid . '/' . $sample_username . '_' . $uuid . '_results.zip';
        $zip_full_name = $base_path . $sample_username . '/' . $uuid . '/' . $sample_username . '_' . $uuid . '_results.zip';

        if (Storage::disk('local')->exists($result_path) && Storage::disk('local')->exists($zip_name)) {
            return response()->download($zip_full_name);
        } else {
            return 'sorry!can not read result.zip!';
        }
    }
}
