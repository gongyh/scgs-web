<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use App\Samples;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        /**
         * copy MultiQC and Kraken report to public/results
         */
        $sample_id = $request->input('sampleID');
        $project_id = Samples::where('id', $sample_id)->value('projects_id');
        $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
        $sample_username = User::where('id', $user_id)->value('name');
        $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
        $path = $base_path . $sample_username . '/' . $uuid . '/results';
        $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $sample_username . '/' . $uuid;
        $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
        $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
        system($multiqc_mkdir);
        system($cp_multiqc);
        system($cp_kraken);

        $filename = Samples::where('id', $sample_id)->value('filename1');
        preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $filename, $matches);
        $file_postfix = $matches[0];
        $file_prefix = Str::before($filename, $file_postfix);
        $file_prefix = explode('/', $file_prefix);
        $file_prefix = end($file_prefix);
        $sample_user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
        $sample_user = User::where('id', $sample_user_id)->value('name');
        $sample_uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        $started = Jobs::where('sample_id', $sample_id)->value('started');
        $finished = Jobs::where('sample_id', $sample_id)->value('finished');
        $period = $finished - $started;
        $command = Jobs::where('sample_id', $sample_id)->value('command');
        return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'sample_id' => $sample_id, 'sample_user' => $sample_user, 'sample_uuid' => $sample_uuid, 'project_id' => $project_id, 'file_prefix' => $file_prefix]);
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
