<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjResultController extends Controller
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
        /**
         * copy MultiQC and Kraken report to public/results
         */
        $project_id = $request->input('projectID');
        $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        $user_id = Jobs::where('project_id', $project_id)->value('user_id');
        $project_username = User::where('id', $user_id)->value('name');
        $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
        $path = $base_path . $project_username . '/' . $uuid . '/results';
        $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $project_username . '/' . $uuid;
        $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
        $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
        $cp_blob = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/blob ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
        system($multiqc_mkdir);
        system($cp_multiqc);
        system($cp_kraken);
        system($cp_blob);

        $project_user_id = Jobs::where('project_id', $project_id)->value('user_id');
        $project_user = User::where('id', $project_user_id)->value('name');
        $project_uuid = Jobs::where('project_id', $project_id)->value('uuid');
        $started = Jobs::where('project_id', $project_id)->value('started');
        $finished = Jobs::where('project_id', $project_id)->value('finished');
        $period = $finished - $started;
        $command = Jobs::where('project_id', $project_id)->value('command');
        return view('RunResult.projsuccessRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'project_id' => $project_id, 'project_user' => $project_user, 'project_uuid' => $project_uuid, 'project_id' => $project_id]);
    }

    public function download_result(Request $request)
    {
        $project_id = $request->input('projectID');
        $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
        $user_id = Jobs::where('project_id', $project_id)->value('user_id');
        $project_username = User::where('id', $user_id)->value('name');
        $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        $result_path  = $project_username . '/' . $uuid . '/results';
        $zip_name = $project_username . '/' . $uuid . '/' . $project_username . '_' . $uuid . '_results.zip';
        $zip_full_name = $base_path . $project_username . '/' . $uuid . '/' . $project_username . '_' . $uuid . '_results.zip';

        if (Storage::disk('local')->exists($result_path) && Storage::disk('local')->exists($zip_name)) {
            return response()->download($zip_full_name);
        } else {
            return 'sorry!can not read result.zip!';
        }
    }
}
