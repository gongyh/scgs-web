<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use App\Samples;
use App\Projects;
use App\Execparams;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ResultController extends Controller
{
    //
    public function failed_running(Request $request)
    {
        $uuid = $request->input('uuid');
        $project_id = Jobs::where('uuid', $uuid)->value('project_id');
        $project_accession = Projects::where('id', $project_id)->value('doi');
        $file_path = $project_accession . '/' . $uuid . '/.nextflow.log';
        $nextflowLog = Storage::get($file_path);
        return view('RunResult.failedRunning', ['nextflowLog' => $nextflowLog]);
    }

    public function success_running(Request $request)
    {
        /**
         * Copy MultiQC and Kraken report to public/results
         */
        if ($request->input('sampleID')) {
            // Samples
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $filename = Samples::where('id', $sample_id)->value('filename1');
            preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(\.1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $filename, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename, $file_postfix);
            $file_prefix = explode('/', $file_prefix);
            $file_prefix = end($file_prefix);
            $sample_user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
            $sample_user = User::where('id', $sample_user_id)->value('name');
            $sample_uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $sample_command = Jobs::where('sample_id', $sample_id)->value('command');
            if (strpos($sample_command, '--euk ')) {
                $species = 'fungus';
            } else {
                $species = 'bacterium';
            }
            $started = Jobs::where('sample_id', $sample_id)->value('started');
            $finished = Jobs::where('sample_id', $sample_id)->value('finished');
            $period = $finished - $started;
            $preseq_array = array();
            array_push($preseq_array, $file_prefix . '_c', $file_prefix . '_lc', $file_prefix . '_gc');
            $command = Jobs::where('sample_id', $sample_id)->value('command');
            return view('RunResult.successRunning', [
                'started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'sample_id' => $sample_id, 'sample_user' => $sample_user, 'sample_uuid' => $sample_uuid,
                'species' => $species, 'project_id' => $project_id, 'project_accession' => $project_accession, 'file_prefix' => $file_prefix, 'preseq_array' => $preseq_array
            ]);
        } else {
            // Projects
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $project_user_id = Jobs::where('project_id', $project_id)->value('user_id');
            $project_user = User::where('id', $project_user_id)->value('name');
            $project_uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $project_command = Jobs::where('project_id', $project_id)->value('command');
            if (strpos($project_command, '--euk ')) {
                $species = 'fungus';
            } else {
                $species = 'bacterium';
            }
            $started = Jobs::where('project_id', $project_id)->value('started');
            $finished = Jobs::where('project_id', $project_id)->value('finished');
            $period = $finished - $started;
            $result_path = $project_accession . '/' . $project_uuid . '/results/';
            $filename_array = array();
            $preseq_array = array();
            $blob_path = $result_path . 'blob/';
            $project_sample_filenames = Samples::where('projects_id', $project_id)->get('filename1');
            if (Storage::disk('local')->exists($blob_path)) {
                $directories = Storage::directories($blob_path);
                foreach ($directories as $directory) {
                    $filename = explode("/", $directory);
                    $filename = end($filename);
                    array_push($filename_array, $filename);
                    array_push($preseq_array, $filename . '_c', $filename . '_lc', $filename . '_gc');
                }
            } else {
                foreach ($project_sample_filenames as $sample_filename) {
                    $sample_filename = $sample_filename['filename1'];
                    preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(\.1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $sample_filename, $matches);
                    $file_postfix = $matches[0];
                    $file_prefix = Str::before($sample_filename, $file_postfix);
                    $file_prefix = explode('/', $file_prefix);
                    $file_prefix = end($file_prefix);
                    array_push($filename_array, $file_prefix);
                    array_push($preseq_array, $file_prefix . '_c', $file_prefix . '_lc', $file_prefix . '_gc');
                }
            }
            $command = Jobs::where('project_id', $project_id)->value('command');
            return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'project_user' => $project_user, 'project_uuid' => $project_uuid, 'project_id' => $project_id, 'project_accession' => $project_accession, 'species' => $species, 'filename_array' => $filename_array, 'preseq_array' => $preseq_array]);
        }
    }

    public function home(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $blobName = $request->input('blob');
            $execParamsJson = Execparams::where('project_id', $project_id)->value('execute_params');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $execParamsJson = Execparams::where('samples_id', $sample_id)->value('execute_params');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $filename = Samples::where('id', $sample_id)->value('filename1');
            preg_match('/(\.fq)?(\.fastq)?(\.gz)?$/', $filename, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename, $file_postfix);
            $file_prefix = explode('/', $file_prefix);
            $file_prefix = end($file_prefix);
            $blobName = str_replace("_R1", "_R", $file_prefix);
            $blobName = str_replace(".R1", ".R", $blobName);
        }
        $execParams = json_decode($execParamsJson, true);
        if (array_key_exists("ver", $execParams) && $execParams["ver"] === "v2") {
            $quast_path = $project_accession . '/' . $uuid . '/results/quast/quast/report.tsv';
        } else {
            $quast_path = $project_accession . '/' . $uuid . '/results/quast/report.tsv';
        }
        $blob_txt_path = $project_accession . '/' . $uuid . '/results/blob/' . $blobName . '/' . $blobName . '.blobDB.table.txt';
        $blob_pic_path = $project_accession . '/' . $uuid . '/results/blob/' . $blobName . '/' . $blobName . '.blobDB.table.txt';
        if (Storage::disk('local')->exists($quast_path) && Storage::disk('local')->exists($blob_txt_path)) {
            // Quast
            $quast_data = Storage::get($quast_path);
            $quast_data = explode("\n", $quast_data);
            $quast_show = array();
            $quast_sh = array();
            $quast_detail = array();
            foreach ($quast_data as $quast) {
                if (strpos($quast, "#") === false) {
                    array_push($quast_show, $quast);
                }
            }
            array_pop($quast_show);
            foreach ($quast_show as $quast_show_str) {
                $quast_sh_str = explode("\t", $quast_show_str);
                array_push($quast_detail, $quast_sh_str);
            }
            // blob_header
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_first_line = $blob_txt[0];
            $blob_header = array();
            $blob = explode("\t", $blob_first_line);
            $len_pos = strpos($blob[0], '#');
            $blob[0] = substr($blob[0], $len_pos + 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 11, 1);
            array_splice($blob, 11, 1);
            array_push($blob_header, $blob);
            // blob_picture
            $blob_pic = Storage::get($blob_pic_path);
            $blob_pic = explode("\n", $blob_pic);
            $blob_pic = array_splice($blob_pic, 10);
            $blob_picture = array();
            $length  = count($blob_pic) > 20000 ? 20000 : count($blob_pic);
            for ($i = 0; $i <= $length - 1; $i++) {
                $blob = explode("\t", $blob_pic[$i]);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob, 6, 1);
                array_splice($blob, 6, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 11, 1);
                array_splice($blob, 11, 1);
                array_push($blob_picture, $blob);
            }
            array_shift($blob_picture);
            $data = array('quast' => $quast_detail, 'blob_header' => $blob_header, 'blob_pic' => $blob_picture);
            return response()->json(['code' => 200, 'data' => $data]);
        } elseif (Storage::disk('local')->exists($quast_path) && !Storage::disk('local')->exists($blob_txt_path)) {
            $quast_data = Storage::get($quast_path);
            $quast_data = explode("\n", $quast_data);
            $quast_show = array();
            $quast_sh = array();
            $quast_detail = array();
            foreach ($quast_data as $quast) {
                if (strpos($quast, "#") === false) {
                    array_push($quast_show, $quast);
                }
            }
            array_pop($quast_show);
            foreach ($quast_show as $quast_show_str) {
                $quast_sh_str = explode("\t", $quast_show_str);
                array_push($quast_detail, $quast_sh_str);
            }
            $blob_table = array();
            $data = array('quast' => $quast_detail, 'blob_table' => $blob_table);
            return response()->json(['code' => 200, 'data' => $data]);
        } elseif (!Storage::disk('local')->exists($quast_path) && Storage::disk('local')->exists($blob_txt_path)) {
            // Blob_header
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_first_line = $blob_txt[0];
            $blob_header = array();
            $blob = explode("\t", $blob_first_line);
            $len_pos = strpos($blob[0], '#');
            $blob[0] = substr($blob[0], $len_pos + 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 11, 1);
            array_splice($blob, 11, 1);
            array_push($blob_header, $blob);
            // blob_picture
            $blob_pic = Storage::get($blob_pic_path);
            $blob_pic = explode("\n", $blob_pic);
            $blob_pic = array_splice($blob_pic, 10);
            $blob_picture = array();
            $length  = count($blob_pic) > 20000 ? 20000 : count($blob_pic);
            for ($i = 0; $i <= $length - 1; $i++) {
                $blob = explode("\t", $blob_pic[$i]);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob, 6, 1);
                array_splice($blob, 6, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 11, 1);
                array_splice($blob, 11, 1);
                array_push($blob_picture, $blob);
            }
            array_shift($blob_picture);
            $data = array('blob_header' => $blob_header, 'blob_pic' => $blob_picture);
            return response()->json(['code' => 200, 'data' => $data]);
        } else {
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    // Download result
    public function download_result(Request $request)
    {
        if ($request->input('sampleID')) {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $result_path  = $project_accession . '/' . $uuid . '/results';
            $zip_name = $project_accession . '/' . $uuid . '/results.zip';
            $zip_full_name = $base_path . $project_accession . '/' . $uuid . '/results.zip';
            if (Storage::disk('local')->exists($result_path) && Storage::disk('local')->exists($zip_name)) {
                return response()->download($zip_full_name);
            } else {
                return 'sorry! Can not read result.zip!';
            }
        } else {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $result_path  = $project_accession . '/' . $uuid . '/results';
            $zip_name = $project_accession . '/' . $uuid . '/results.zip';
            $zip_full_name = $base_path . $project_accession . '/' . $uuid . '/results.zip';
            if (Storage::disk('local')->exists($result_path) && Storage::disk('local')->exists($zip_name)) {
                return response()->download($zip_full_name);
            } else {
                return 'sorry! Can not read result.zip!';
            }
        }
    }

    // quast result
    public function quast(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $execParams = Execparams::where('project_id', $project_id)->value('execute_params');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $execParams = Execparams::where('samples_id', $sample_id)->value('execute_params');
        }
        if (array_key_exists("ver", $execParams) && $execParams["ver"] === "v2") {
            $quast_path = $project_accession . '/' . $uuid . '/results/quast/quast/report.tsv';
        } else {
            $quast_path = $project_accession . '/' . $uuid . '/results/quast/report.tsv';
        }
        if (Storage::disk('disk')->exists($quast_path)) {
            $quast_data = Storage::get($quast_path);
            $quast_data = explode("\n", $quast_data);
            $quast_show = array();
            $quast_sh = array();
            $quast_detail = array();
            foreach ($quast_data as $quast) {
                if (strpos($quast, "#") === false) {
                    array_push($quast_show, $quast);
                }
            }
            array_pop($quast_show);
            foreach ($quast_show as $quast_show_str) {
                $quast_sh_str = explode("\t", $quast_show_str);
                array_push($quast_detail, $quast_sh_str);
            }
            $data = array('data' => $quast_detail);
            return response()->json(['code' => 200, 'data' => $data]);
        } else {
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    public function get_blob_header(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        $blob = $request->input('blob');
        $blob_txt_path = $project_accession . '/' . $uuid . '/results/blob/' . $blob . '/' . $blob . '.blobDB.table.txt';
        if (Storage::disk('local')->exists($blob_txt_path)) {
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_first_line = $blob_txt[0];
            $blob_header = array();
            $blob = explode("\t", $blob_first_line);
            $len_pos = strpos($blob[0], '#');
            $blob[0] = substr($blob[0], $len_pos + 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 11, 1);
            array_splice($blob, 11, 1);
            array_push($blob_header, $blob);
        }
        $data = array('blob_header' => $blob_header);
        return response()->json(['code' => 200, 'data' => $data]);
    }

    public function blob_body(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $blobName = $request->input('blob');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $filename = Samples::where('id', $sample_id)->value('filename1');
            preg_match('/(\.fq)?(\.fastq)?(\.gz)?$/', $filename, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename, $file_postfix);
            $file_prefix = explode('/', $file_prefix);
            $file_prefix = end($file_prefix);
            $blobName = str_replace("_R1", "_R", $file_prefix);
            $blobName = str_replace(".R1", ".R", $blobName);
        }
        $blob_txt_path = $project_accession . '/' . $uuid . '/results/blob/' . $blobName . '/' . $blobName . '.blobDB.table.txt';
        if (Storage::disk('local')->exists($blob_txt_path)) {
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            array_shift($blob_txt);
            $blob_table = array();
            $length  = count($blob_txt) > 20000 ? 20000 : count($blob_txt);
            for ($i = 0; $i <= $length - 1; $i++) {
                $blob = explode("\t", $blob_txt[$i]);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob, 6, 1);
                array_splice($blob, 6, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 7, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 8, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 9, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 10, 1);
                array_splice($blob, 11, 1);
                array_splice($blob, 11, 1);
                array_push($blob_table, $blob);
            }
            return response()->json(['data' => $blob_table]);
        } else {
            return response()->json(['data' => 'failed']);
        }
    }

    public function blob_classify(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $blobName = $request->input('blob');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $filename = Samples::where('id', $sample_id)->value('filename1');
            preg_match('/(\.fq)?(\.fastq)?(\.gz)?$/', $filename, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename, $file_postfix);
            $file_prefix = explode('/', $file_prefix);
            $file_prefix = end($file_prefix);
            $blobName = str_replace("_R1", "_R", $file_prefix);
            $blobName = str_replace(".R1", ".R", $blobName);
        }
        $blob_path = $project_accession . '/' . $uuid . '/results/blob/' . $blobName . '/' . $blobName . '.blobDB.table.txt';

        // data init
        $blob_txt = Storage::get($blob_path);
        $blob_txt = explode("\n", $blob_txt);
        $blob_txt = array_splice($blob_txt, 10);
        $blob_data = array();
        $length  = count($blob_txt) > 20000 ? 20000 : count($blob_txt);
        for ($i = 0; $i <= $length - 1; $i++) {
            $blob = explode("\t", $blob_txt[$i]);
            $len_pos = strpos($blob[0], '_length');
            $blob[0] = substr($blob[0], 0, $len_pos);
            array_splice($blob, 6, 1);
            array_splice($blob, 6, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 7, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 8, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 9, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 10, 1);
            array_splice($blob, 11, 1);
            array_splice($blob, 11, 1);
            array_push($blob_data, $blob);
        }
        array_shift($blob_data);
        return response()->json(['code' => 200, 'data' => $blob_data]);
    }

    // multiQC result
    public function multiqc(Request $request)
    {
        if ($request->input('sample_uuid')) {
            $sample_uuid = $request->input('sample_uuid');
            $sample_id = Jobs::where('uuid', $sample_uuid)->value('sample_id');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $relative_path = $project_accession . '/' . $sample_uuid . '/results/MultiQC/multiqc_report.html';
            $multiqc_path = $base_path . $relative_path;
            if (Storage::disk('local')->exists($relative_path)) {
                return response()->file($multiqc_path);
            } else {
                return abort('404');
            }
        } else {
            $project_uuid = $request->input('project_uuid');
            $project_id = Jobs::where('uuid', $project_uuid)->value('project_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $relative_path = $project_accession . '/' . $project_uuid . '/results/MultiQC/multiqc_report.html';
            $multiqc_path = $base_path . $relative_path;
            if (Storage::disk('local')->exists($relative_path)) {
                return response()->file($multiqc_path);
            } else {
                return abort('404');
            }
        }
    }

    // kraken result
    public function kraken(Request $request)
    {
        $sample_name = $request->input('sample_name');
        if ($request->input('sample_uuid')) {
            $sample_uuid = $request->input('sample_uuid');
            $sample_id = Jobs::where('uuid', $sample_uuid)->value('sample_id');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            if (Storage::exists($project_accession . '/' . $sample_uuid . '/results/kraken/')) {
                $files = Storage::files($project_accession . '/' . $sample_uuid . '/results/kraken/');
                foreach ($files as $file) {
                    $pattern = '/.*?' . $sample_name . '.*?(.html)$/is';
                    if (preg_match($pattern, $file)) {
                        $relative_path = $file;
                    }
                }
                $krona_path = $base_path . $relative_path;
                if (Storage::disk('local')->exists($relative_path)) {
                    return response()->file($krona_path);
                } else {
                    return abort('404');
                }
            } else {
                return abort('404');
            }
        } else {
            $project_uuid = $request->input('project_uuid');
            $project_id = Jobs::where('uuid', $project_uuid)->value('project_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            if (Storage::exists($project_accession . '/' . $project_uuid . '/results/kraken/')) {
                $files = Storage::files($project_accession . '/' . $project_uuid . '/results/kraken/');
                foreach ($files as $file) {
                    $pattern = '/.*?' . $sample_name . '.*?(.html)$/is';
                    if (preg_match($pattern, $file)) {
                        $relative_path = $file;
                    }
                }
                $krona_path = $base_path . $relative_path;
                if (Storage::disk('local')->exists($relative_path)) {
                    return response()->file($krona_path);
                } else {
                    return abort('404');
                }
            } else {
                return abort('404');
            }
        }
    }

    // blobTools result
    public function blob_result(Request $request)
    {
        $sample_name = $request->input('sample_name');
        if ($request->input('sample_uuid')) {
            $sample_uuid = $request->input('sample_uuid');
            $sample_id = Jobs::where('uuid', $sample_uuid)->value('sample_id');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            if (Storage::exists($project_accession . '/' . $sample_uuid . '/results/blob/' . $sample_name . '/')) {
                $files = Storage::files($project_accession . '/' . $sample_uuid . '/results/blob/' . $sample_name . '/');
                foreach ($files as $file) {
                    $pattern = '/.*?(family)+.*?(.png)$/is';
                    if (preg_match($pattern, $file)) {
                        $relative_path = $file;
                    }
                }
                $blob_path = $base_path . $relative_path;
                if (Storage::disk('local')->exists($relative_path)) {
                    return response()->file($blob_path);
                } else {
                    return abort('404');
                }
            } else {
                return abort('404');
            }
        } else {
            $project_uuid = $request->input('project_uuid');
            $project_id = Jobs::where('uuid', $project_uuid)->value('project_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            if (Storage::exists($project_accession . '/' . $project_uuid . '/results/blob/' . $sample_name . '/')) {
                $files = Storage::files($project_accession . '/' . $project_uuid . '/results/blob/' . $sample_name . '/');
                foreach ($files as $file) {
                    $pattern = '/.*?(family)+.*?(.png)$/is';
                    if (preg_match($pattern, $file)) {
                        $relative_path = $file;
                    }
                }
                $blob_path = $base_path . $relative_path;
                if (Storage::disk('local')->exists($relative_path)) {
                    return response()->file($blob_path);
                } else {
                    return abort('404');
                }
            } else {
                return abort('404');
            }
        }
    }

    // preseq result
    public function preseq(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        if ($request->input('preseq')) {
            $preseq = $request->input('preseq');
            $tag_pos = strpos($preseq, '_');
            $tag = substr($preseq, 0, $tag_pos);
            $preseq_path = null;
            if (Storage::exists($project_accession . '/' . $uuid . '/results/preseq/')) {
                $files = Storage::files($project_accession . '/' . $uuid . '/results/preseq/');
                foreach ($files as $file) {
                    if (strpos($preseq, '_c')) {
                        $pattern = '/(' . $tag . ')+.*?(_c)+(.txt)$/is';
                        if (preg_match($pattern, $file)) {
                            $preseq_path = $file;
                        }
                    } elseif (strpos($preseq, '_lc')) {
                        $pattern = '/(' . $tag . ')+.*?(_lc)+(.txt)$/is';
                        if (preg_match($pattern, $file)) {
                            $preseq_path = $file;
                        }
                    } else {
                        $pattern = '/(' . $tag . ')+.*?(_gc)+(.txt)$/is';
                        if (preg_match($pattern, $file)) {
                            $preseq_path = $file;
                        }
                    }
                }
            } else {
                return response()->json(['code' => 404, 'data' => 'failed']);
            }
            if (strpos($preseq, '_c')) {
                if (isset($preseq_path) && Storage::disk('local')->exists($preseq_path)) {
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
                }
            } elseif (strpos($preseq, '_lc')) {
                if (isset($preseq_path) && Storage::disk('local')->exists($preseq_path)) {
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
                }
            } else {
                if (isset($preseq_path) && Storage::disk('local')->exists($preseq_path)) {
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
                }
            }
        }
    }

    // arg result
    public function arg(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        if ($request->input('arg')) {
            $arg = $request->input('arg');
            $arg_path = $project_accession . '/' . $uuid . '/results/ARG/' . $arg . '/results_tab.tsv';
            if (Storage::disk('local')->exists($arg_path)) {
                $arg_data = Storage::get($arg_path);
                $arg_data = explode("\n", $arg_data);
                array_pop($arg_data);
                $ARG_data = array();
                foreach ($arg_data as $arg) {
                    $arg = explode("\t", $arg);
                    array_push($ARG_data, $arg);
                }
                return response()->json(['code' => 200, 'data' => $ARG_data]);
            } else {
                return response()->json(['code' => 404, 'data' => 'failed']);
            }
        }
    }

    // bowtie result
    public function bowtie(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        $bowtie = $request->input('bowtie');
        $base_path = $project_accession . '/' . $uuid . '/results/bowtie2/stats/';
        if (Storage::disk('local')->exists($base_path)) {
            $files = Storage::files($base_path);
            foreach ($files as $file) {
                $pattern = '/' . $bowtie . '.*?(.txt)$/is';
                if (preg_match($pattern, $file)) {
                    $bowtie_path = $file;
                }
            }
            $bowtie_data = Storage::get($bowtie_path);
            $bowtie_data = explode("\n", $bowtie_data);
            $bowtie_sn = array();
            foreach ($bowtie_data as $bowtie) {
                if (strpos($bowtie, "SN") !== false) {
                    array_push($bowtie_sn, $bowtie);
                }
            }
            array_splice($bowtie_sn, 0, 1);
            $bowtie_header = array();
            $bowtie_stats = array();
            foreach ($bowtie_sn as $bowtie) {
                $bowtie_arr = explode("\t", $bowtie);
                $bowtie_head = $bowtie_arr[1];
                $bowtie_pop = $bowtie_arr[2];
                array_push($bowtie_stats, $bowtie_pop);
                array_push($bowtie_header, $bowtie_head);
            }
            $bowtie = array();
            array_push($bowtie, $bowtie_header, $bowtie_stats);
            return response()->json(['code' => 200, 'data' => $bowtie]);
        } else {
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    // checkM result
    public function checkM(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        $checkM_path =  $project_accession . '/' . $uuid . '/results/CheckM/spades_checkM.txt';
        if (Storage::disk('local')->exists($checkM_path)) {
            $checkM = Storage::get($checkM_path);
            $checkM = explode("\n", $checkM);
            array_pop($checkM);
            $checkM_data = array();
            foreach ($checkM as $checkM_d) {
                $checkM_d = explode("\t", $checkM_d);
                array_push($checkM_data, $checkM_d);
            }
            return response()->json(['code' => 200, 'data' => $checkM_data]);
        } else {
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    // EukCC result
    public function eukcc(Request $request)
    {
        if ($request->input('projectID')) {
            $project_id = $request->input('projectID');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
        } else {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
        }
        $EukCC = $request->input('EukCC');
        $EukCC_path = $project_accession . '/' . $uuid . '/results/EukCC/' . $EukCC . '/eukcc.tsv';
        if (Storage::disk('local')->exists($EukCC_path)) {
            $EukCC_data = Storage::get($EukCC_path);
            $EukCC_data = explode("\n", $EukCC_data);
            array_pop($EukCC_data);
            $EukCC_header = explode("\t", $EukCC_data[0]);
            array_shift($EukCC_data);
            $EukCC_body = array();
            foreach ($EukCC_data as $Eukcc_d) {
                $d = explode("\t", $Eukcc_d);
                array_push($EukCC_body, $d);
            }
            $data = array('EukCC_header' => $EukCC_header, 'EukCC_body' => $EukCC_body);
            return response()->json(['code' => 200, 'data' => $data]);
        } else {
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }
}
