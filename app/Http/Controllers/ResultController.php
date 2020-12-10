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
        if ($request->input('sampleID')) {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
            $sample_username = User::where('id', $user_id)->value('name');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $path = $base_path . $sample_username . '/' . $uuid . '/results';
            $quast_path = $sample_username . '/' . $uuid . '/results/quast/report.tsv';
            if (Storage::disk('local')->exists($quast_path)) {
                $quast_data = Storage::get($quast_path);
            }
            $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $sample_username . '/' . $uuid;
            $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
            $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
            $cp_blob = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/blob ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
            system($multiqc_mkdir);
            system($cp_multiqc);
            system($cp_kraken);
            system($cp_blob);

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
            $preseq_array = array();
            array_push($preseq_array, $file_prefix . '_c', $file_prefix . '_lc', $file_prefix . '_gc');
            $command = Jobs::where('sample_id', $sample_id)->value('command');
            return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'sample_id' => $sample_id, 'sample_user' => $sample_user, 'sample_uuid' => $sample_uuid, 'project_id' => $project_id, 'file_prefix' => $file_prefix, 'preseq_array' => $preseq_array]);
        } else {
            $project_id = $request->input('projectID');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $user_id = Jobs::where('project_id', $project_id)->value('user_id');
            $project_username = User::where('id', $user_id)->value('name');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $path = $base_path . $project_username . '/' . $uuid . '/results';
            $quast_path = $project_username . '/' . $uuid . '/results/quast/report.tsv';
            if (Storage::disk('local')->exists($quast_path)) {
                $quast_data = Storage::get($quast_path);
            }
            $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $project_username . '/' . $uuid;
            $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
            $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
            $cp_blob = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/blob ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
            $cp_preseq = 'if [ -d ' . $path . '/preseq ]; then cp -r ' . $path . '/preseq ' . public_path() . '/results/' . $project_username . '/' . $uuid . '; fi';
            system($multiqc_mkdir);
            system($cp_multiqc);
            system($cp_kraken);
            system($cp_blob);
            system($cp_preseq);

            $project_user_id = Jobs::where('project_id', $project_id)->value('user_id');
            $project_user = User::where('id', $project_user_id)->value('name');
            $project_uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $started = Jobs::where('project_id', $project_id)->value('started');
            $finished = Jobs::where('project_id', $project_id)->value('finished');
            $period = $finished - $started;
            $project_sample_filenames = Samples::where('projects_id', $project_id)->get('filename1');
            $filename_array = array();
            $preseq_array = array();
            foreach ($project_sample_filenames as $sample_filename) {
                $sample_filename = $sample_filename['filename1'];
                preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $sample_filename, $matches);
                $file_postfix = $matches[0];
                $file_prefix = Str::before($sample_filename, $file_postfix);
                $file_prefix = explode('/', $file_prefix);
                $file_prefix = end($file_prefix);
                array_push($filename_array, $file_prefix);
                array_push($preseq_array, $file_prefix . '_c', $file_prefix . '_lc', $file_prefix . '_gc');
            }
            $command = Jobs::where('project_id', $project_id)->value('command');
            return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'project_user' => $project_user, 'project_uuid' => $project_uuid, 'project_id' => $project_id, 'filename_array' => $filename_array, 'preseq_array' => $preseq_array]);
        }
    }

    public function download_result(Request $request)
    {
        if ($request->input('sampleID')) {
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
        } else {
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

    public function ajax(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $user_id = Jobs::where('project_id', $project_id)->value('user_id');
            $username = User::where('id', $user_id)->value('name');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
            $username = User::where('id', $user_id)->value('name');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        if ($request->input('preseq')) {
            $preseq = $request->input('preseq');
            $preseq_path = $username . '/' . $uuid . '/results/preseq/' . $preseq . '.txt';
            if (strpos($preseq, '_c')) {
                if (Storage::disk('local')->exists($preseq_path)) {
                    $preseq_data = Storage::get($preseq_path);
                    $preseq_data = str_replace('total_reads', '', $preseq_data);
                    $preseq_data = str_replace('distinct_reads', '', $preseq_data);
                    $preseq_data = preg_split('/\s+/is', $preseq_data);
                    array_splice($preseq_data, 0, 1);
                    array_pop($preseq_data);
                    $x = array();
                    $y = array();
                    $data = array();
                    for ($i = 0; $i < count($preseq_data); $i += 2) {
                        array_push($x, $preseq_data[$i]);
                    }
                    for ($i = 1; $i < count($preseq_data); $i += 2) {
                        array_push($y, $preseq_data[$i]);
                    }
                    array_push($data, $x, $y);
                    return response()->json(['code' => 200, 'data' => $data]);
                } else {
                    return response()->json(['code' => 201, 'data' => 'failed']);
                }
            } elseif (strpos($preseq, '_lc')) {
                if (Storage::disk('local')->exists($preseq_path)) {
                    $preseq_data = Storage::get($preseq_path);
                    $preseq_data = str_replace('TOTAL_READS', '', $preseq_data);
                    $preseq_data = str_replace('EXPECTED_DISTINCT', '', $preseq_data);
                    $preseq_data = str_replace('LOWER_0.95CI', '', $preseq_data);
                    $preseq_data = str_replace('UPPER_0.95CI', '', $preseq_data);
                    $preseq_data = preg_split('/\s+/is', $preseq_data);
                    array_splice($preseq_data, 0, 1);
                    $total_reads = array();
                    $expected_distinct = array();
                    $lower_095ci = array();
                    $upper_095ci = array();
                    $data = array();
                    for ($i = 0; $i < count($preseq_data); $i += 4) {
                        array_push($total_reads, $preseq_data[$i]);
                    }
                    for ($i = 1; $i < count($preseq_data); $i += 4) {
                        array_push($expected_distinct, $preseq_data[$i]);
                    }
                    for ($i = 2; $i < count($preseq_data); $i += 4) {
                        array_push($lower_095ci, $preseq_data[$i]);
                    }
                    for ($i = 3; $i < count($preseq_data); $i += 4) {
                        array_push($upper_095ci, $preseq_data[$i]);
                    }
                    array_push($data, $total_reads, $expected_distinct, $lower_095ci, $upper_095ci);
                    return response()->json(['code' => 200, 'data' => $data]);
                } else {
                    return response()->json(['code' => 200, 'data' => 'failed']);
                }
            } else {
                if (Storage::disk('local')->exists($preseq_path)) {
                    $preseq_data = Storage::get($preseq_path);
                    $preseq_data = str_replace('TOTAL_BASES', '', $preseq_data);
                    $preseq_data = str_replace('EXPECTED_COVERED_BASES', '', $preseq_data);
                    $preseq_data = str_replace('LOWER_95%CI', '', $preseq_data);
                    $preseq_data = str_replace('UPPER_95%CI', '', $preseq_data);
                    $preseq_data = preg_split('/\s+/is', $preseq_data);
                    array_splice($preseq_data, 0, 1);
                    $total_bases = array();
                    $expected_covered_bases = array();
                    $lower_095ci = array();
                    $upper_095ci = array();
                    $data = array();
                    for ($i = 0; $i < count($preseq_data); $i += 4) {
                        array_push($total_bases, $preseq_data[$i]);
                    }
                    for ($i = 1; $i < count($preseq_data); $i += 4) {
                        array_push($expected_covered_bases, $preseq_data[$i]);
                    }
                    for ($i = 2; $i < count($preseq_data); $i += 4) {
                        array_push($lower_095ci, $preseq_data[$i]);
                    }
                    for ($i = 3; $i < count($preseq_data); $i += 4) {
                        array_push($upper_095ci, $preseq_data[$i]);
                    }
                    array_push($data, $total_bases, $expected_covered_bases, $lower_095ci, $upper_095ci);
                    return response()->json(['code' => 200, 'data' => $data]);
                } else {
                    return response()->json(['code' => 200, 'data' => 'failed']);
                }
            }
        } elseif ($request->input('arg')) {
            $arg = $request->input('arg');
            $arg_path = $username . '/' . $uuid . '/results/ARG/' . $arg . '/results_tab.tsv';
            if (Storage::disk('local')->exists($arg_path)) {
                $arg_data = Storage::get($arg_path);
                $arg_data = explode("\n", $arg_data);
                array_splice($arg_data, 0, 1);
                array_pop($arg_data);
                $ARG_data = array();
                foreach ($arg_data as $arg) {
                    $arg = explode("\t", $arg);
                    array_push($ARG_data, $arg);
                }
                return response()->json(['code' => 200, 'data' => $ARG_data]);
            } else {
                return response()->json(['code' => 201, 'data' => 'failed']);
            }
        }
    }
}
