<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use App\Samples;
use App\Projects;
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
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $path = $base_path . $project_accession . '/' . $uuid . '/results';
            $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $project_accession . '/' . $uuid;
            $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
            $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
            $cp_blob = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/blob ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
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
            return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'sample_id' => $sample_id, 'sample_user' => $sample_user, 'sample_uuid' => $sample_uuid, 'project_id' => $project_id, 'project_accession' => $project_accession , 'file_prefix' => $file_prefix, 'preseq_array' => $preseq_array]);
        } else {
            $project_id = $request->input('projectID');
            $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $project_accession = Projects::where('id', $project_id)->value('doi');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $path = $base_path . $project_accession . '/' . $uuid . '/results';
            $multiqc_mkdir = 'cd ' . public_path() . '/results && mkdir -p ' . $project_accession . '/' . $uuid;
            $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
            $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
            $cp_blob = 'if [ -d ' . $path . '/kraken ]; then cp -r ' . $path . '/blob ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
            $cp_preseq = 'if [ -d ' . $path . '/preseq ]; then cp -r ' . $path . '/preseq ' . public_path() . '/results/' . $project_accession . '/' . $uuid . '; fi';
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
            $filename_array = array();
            $preseq_array = array();
            $project_sample_filenames = Samples::where('projects_id', $project_id)->get('filename1');
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
            return view('RunResult.successRunning', ['started' => $started, 'finished' => $finished, 'period' => $period, 'command' => $command, 'project_user' => $project_user, 'project_uuid' => $project_uuid, 'project_id' => $project_id, 'project_accession' => $project_accession , 'filename_array' => $filename_array, 'preseq_array' => $preseq_array]);
        }
    }

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
                return 'sorry!can not read result.zip!';
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
                return 'sorry!can not read result.zip!';
            }
        }
    }

    public function ajax(Request $request)
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
            $preseq_path = $project_accession . '/' . $uuid . '/results/preseq/' . $preseq . '.txt';
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
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
                    return response()->json(['code' => 404, 'data' => 'failed']);
                }
            }
        } elseif ($request->input('arg')) {
            $arg = $request->input('arg');
            $arg_path = $project_accession . '/' . $uuid . '/results/ARG/' . $arg . '/results_tab.tsv';
            if (Storage::disk('local')->exists($arg_path)) {
                $arg_data = Storage::get($arg_path);
                $arg_data = explode("\n", $arg_data);
                array_pop($arg_data);
                $ARG_data = array();
                $arg_detail = array();
                foreach ($arg_data as $arg) {
                    $arg = explode("\t", $arg);
                    array_push($ARG_data, $arg);
                }
                return response()->json(['code' => 200, 'data' => $ARG_data]);
            } else {
                return response()->json(['code' => 404, 'data' => 'failed']);
            }
        } elseif ($request->input('bowtie')) {
            $bowtie = $request->input('bowtie');
            $bowtie_path = $project_accession . '/' . $uuid . '/results/bowtie2/stats/' . $bowtie . '.stats.txt';
            if (Storage::disk('local')->exists($bowtie_path)) {
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
    }

    public function home(Request $request)
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
        $blob_pic_val = $request->input('blob_pic');
        $quast_path = $project_accession . '/' . $uuid . '/results/quast/report.tsv';
        $blob_txt_path = $project_accession . '/' . $uuid .'/results/blob/' . $blob . '/' . $blob . '.blobDB.table.txt';
        $blob_pic_path = $project_accession . '/' . $uuid .'/results/blob/' . $blob_pic_val . '/' . $blob_pic_val . '.blobDB.table.txt';
        $project_sample_filenames = Samples::where('projects_id', $project_id)->get('filename1');
        $sample_sum = count($project_sample_filenames);
        if (Storage::disk('local')->exists($quast_path) && Storage::disk('local')->exists($blob_txt_path)) {
            // quast
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
            foreach($quast_show as $quast_show_str){
                $quast_sh_str = explode("\t", $quast_show_str);
                array_push($quast_detail, $quast_sh_str);
            }
            // blob_txt
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_table = array();
            foreach($blob_txt as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob,6,1);
                array_splice($blob,6,1);
                array_splice($blob,7,1);
                array_splice($blob,7,1);
                array_splice($blob,8,1);
                array_splice($blob,8,1);
                array_splice($blob,9,1);
                array_splice($blob,9,1);
                array_splice($blob,10,1);
                array_splice($blob,10,1);
                array_splice($blob,11,1);
                array_splice($blob,11,1);
                array_push($blob_table,$blob);
            }
            // blob_picture
            $blob_pic = Storage::get($blob_pic_path);
            $blob_pic = explode("\n", $blob_pic);
            $blob_pic = array_splice($blob_pic, 10);

            // gc
            $Proteobacteria_gc = array();
            $Chordata_gc = array();
            $Cyanobacteria_gc = array();
            $Actinobacteria_gc = array();
            $Firmicutes_gc = array();
            $Basidiomycota_gc = array();
            $Nucleocytoviricota_gc = array();

            // coverage
            $Proteobacteria_cov = array();
            $Chordata_cov = array();
            $Cyanobacteria_cov = array();
            $Actinobacteria_cov = array();
            $Firmicutes_cov = array();
            $Basidiomycota_cov = array();
            $Nucleocytoviricota_cov = array();

            // name
            $Proteobacteria_name = array();
            $Chordata_name = array();
            $Cyanobacteria_name = array();
            $Actinobacteria_name = array();
            $Firmicutes_name = array();
            $Basidiomycota_name = array();
            $Nucleocytoviricota_name = array();

            // length
            $Proteobacteria_length = array();
            $Chordata_length = array();
            $Cyanobacteria_length = array();
            $Actinobacteria_length = array();
            $Firmicutes_length = array();
            $Basidiomycota_length = array();
            $Nucleocytoviricota_length = array();

            foreach($blob_pic as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                // phylum
                switch($blob[8])
                {
                    case 'Proteobacteria':
                        array_push($Proteobacteria_name, $blob[0]);
                        array_push($Proteobacteria_length, $blob[1]);
                        array_push($Proteobacteria_gc, $blob[2]);
                        array_push($Proteobacteria_cov, $blob[4]);
                        break;
                    case 'Chordata':
                        array_push($Chordata_name, $blob[0]);
                        array_push($Chordata_length, $blob[1]);
                        array_push($Chordata_gc, $blob[2]);
                        array_push($Chordata_cov, $blob[4]);
                        break;
                    case 'Cyanobacteria':
                        array_push($Cyanobacteria_name, $blob[0]);
                        array_push($Cyanobacteria_length, $blob[1]);
                        array_push($Cyanobacteria_gc, $blob[2]);
                        array_push($Cyanobacteria_cov, $blob[4]);
                        break;
                    case 'Actinobacteria':
                        array_push($Actinobacteria_name, $blob[0]);
                        array_push($Actinobacteria_length, $blob[1]);
                        array_push($Actinobacteria_gc, $blob[2]);
                        array_push($Actinobacteria_cov, $blob[4]);
                        break;
                    case 'Firmicutes':
                        array_push($Firmicutes_name, $blob[0]);
                        array_push($Firmicutes_length, $blob[1]);
                        array_push($Firmicutes_gc, $blob[2]);
                        array_push($Firmicutes_cov, $blob[4]);
                        break;
                    case 'Basidiomycota':
                        array_push($Basidiomycota_name, $blob[0]);
                        array_push($Basidiomycota_length, $blob[1]);
                        array_push($Basidiomycota_gc, $blob[2]);
                        array_push($Basidiomycota_cov, $blob[4]);
                        break;
                    case 'Nucleocytoviricota':
                        array_push($Nucleocytoviricota_name, $blob[0]);
                        array_push($Nucleocytoviricota_length, $blob[1]);
                        array_push($Nucleocytoviricota_gc, $blob[2]);
                        array_push($Nucleocytoviricota_cov, $blob[4]);
                        break;
                    default:
                        break;
                }

            }
            $Proteobacteria = array('Proteobacteria_name' => $Proteobacteria_name, 'Proteobacteria_length' => $Proteobacteria_length, 'Proteobacteria_gc' => $Proteobacteria_gc, 'Proteobacteria_cov' => $Proteobacteria_gc);
            $Chordata = array('Chordata_name' => $Chordata_name, 'Chordata_length' => $Chordata_length, 'Chordata_gc' => $Chordata_gc, 'Chordata_cov' => $Chordata_cov);
            $Cyanobacteria = array('Cyanobacteria_name' => $Cyanobacteria_name, 'Cyanobacteria_length' => $Cyanobacteria_length, 'Cyanobacteria_gc' => $Cyanobacteria_gc, 'Cyanobacteria_cov' => $Cyanobacteria_cov);
            $Actinobacteria = array('Actinobacteria_name' => $Actinobacteria_name, 'Actinobacteria_length' => $Actinobacteria_length, 'Actinobacteria_gc' => $Actinobacteria_gc, 'Actinobacteria_cov' => $Actinobacteria_cov);
            $Firmicutes = array('Firmicutes_name' => $Firmicutes_name, 'Firmicutes_length' => $Firmicutes_length, 'Firmicutes_gc' => $Firmicutes_gc, 'Firmicutes_cov' => $Firmicutes_cov);
            $Basidiomycota = array('Basidiomycota_name' => $Basidiomycota_name, 'Basidiomycota_length' => $Basidiomycota_length, 'Basidiomycota_gc' => $Basidiomycota_gc, 'Basidiomycota_cov' => $Basidiomycota_cov);
            $Nucleocytoviricota = array('Nucleocytoviricota_name' => $Nucleocytoviricota_name, 'Nucleocytoviricota_length' => $Nucleocytoviricota_length, 'Nucleocytoviricota_gc' => $Nucleocytoviricota_gc, 'Nucleocytoviricota_cov' => $Nucleocytoviricota_cov);

            $blob_picture = array('Proteobacteria' => $Proteobacteria, 'Chordata' => $Chordata, 'Cyanobacteria' => $Cyanobacteria, 'Actinobacteria' => $Actinobacteria, 'Firmicutes' => $Firmicutes, 'Basidiomycota' => $Basidiomycota, 'Nucleocytoviricota' => $Nucleocytoviricota);
            $data = array('quast' => $quast_detail, 'blob_table' => $blob_table,'blob_pic' => $blob_picture);
            return response()->json(['code' => 200, 'data' => $data]);
        } elseif(Storage::disk('local')->exists($quast_path) && !Storage::disk('local')->exists($blob_txt_path)){
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
            foreach($quast_show as $quast_show_str){
                $quast_sh_str = explode("\t",$quast_show_str);
                array_push($quast_detail, $quast_sh_str);
            }
            $blob_table = array();
            $data = array('quast' => $quast_detail, 'blob_table' => $blob_table);
            return response()->json(['code' => 200, 'data' => $data]);
        } elseif(!Storage::disk('local')->exists($quast_path) && Storage::disk('local')->exists($blob_txt_path)){
            // blob_txt
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_table = array();
            foreach($blob_txt as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob,6,1);
                array_splice($blob,6,1);
                array_splice($blob,7,1);
                array_splice($blob,7,1);
                array_splice($blob,8,1);
                array_splice($blob,8,1);
                array_splice($blob,9,1);
                array_splice($blob,9,1);
                array_splice($blob,10,1);
                array_splice($blob,10,1);
                array_splice($blob,11,1);
                array_splice($blob,11,1);
                array_push($blob_table, $blob);
            }
            // blob_picture
            $blob_pic = Storage::get($blob_pic_path);
            $blob_pic = explode("\n", $blob_pic);
            $blob_pic = array_splice($blob_pic, 10);

            // gc
            $Proteobacteria_gc = array();
            $Chordata_gc = array();
            $Cyanobacteria_gc = array();
            $Actinobacteria_gc = array();
            $Firmicutes_gc = array();
            $Basidiomycota_gc = array();
            $Nucleocytoviricota_gc = array();

            // coverage
            $Proteobacteria_cov = array();
            $Chordata_cov = array();
            $Cyanobacteria_cov = array();
            $Actinobacteria_cov = array();
            $Firmicutes_cov = array();
            $Basidiomycota_cov = array();
            $Nucleocytoviricota_cov = array();

            // name
            $Proteobacteria_name = array();
            $Chordata_name = array();
            $Cyanobacteria_name = array();
            $Actinobacteria_name = array();
            $Firmicutes_name = array();
            $Basidiomycota_name = array();
            $Nucleocytoviricota_name = array();

            // length
            $Proteobacteria_length = array();
            $Chordata_length = array();
            $Cyanobacteria_length = array();
            $Actinobacteria_length = array();
            $Firmicutes_length = array();
            $Basidiomycota_length = array();
            $Nucleocytoviricota_length = array();

            foreach($blob_pic as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                // phylum
                switch($blob[8])
                {
                    case 'Proteobacteria':
                        array_push($Proteobacteria_name, $blob[0]);
                        array_push($Proteobacteria_length, $blob[1]);
                        array_push($Proteobacteria_gc, $blob[2]);
                        array_push($Proteobacteria_cov, $blob[4]);
                        break;
                    case 'Chordata':
                        array_push($Chordata_name, $blob[0]);
                        array_push($Chordata_length, $blob[1]);
                        array_push($Chordata_gc, $blob[2]);
                        array_push($Chordata_cov, $blob[4]);
                        break;
                    case 'Cyanobacteria':
                        array_push($Cyanobacteria_name, $blob[0]);
                        array_push($Cyanobacteria_length, $blob[1]);
                        array_push($Cyanobacteria_gc, $blob[2]);
                        array_push($Cyanobacteria_cov, $blob[4]);
                        break;
                    case 'Actinobacteria':
                        array_push($Actinobacteria_name, $blob[0]);
                        array_push($Actinobacteria_length, $blob[1]);
                        array_push($Actinobacteria_gc, $blob[2]);
                        array_push($Actinobacteria_cov, $blob[4]);
                        break;
                    case 'Firmicutes':
                        array_push($Firmicutes_name, $blob[0]);
                        array_push($Firmicutes_length, $blob[1]);
                        array_push($Firmicutes_gc, $blob[2]);
                        array_push($Firmicutes_cov, $blob[4]);
                        break;
                    case 'Basidiomycota':
                        array_push($Basidiomycota_name, $blob[0]);
                        array_push($Basidiomycota_length, $blob[1]);
                        array_push($Basidiomycota_gc, $blob[2]);
                        array_push($Basidiomycota_cov, $blob[4]);
                        break;
                    case 'Nucleocytoviricota':
                        array_push($Nucleocytoviricota_name, $blob[0]);
                        array_push($Nucleocytoviricota_length, $blob[1]);
                        array_push($Nucleocytoviricota_gc, $blob[2]);
                        array_push($Nucleocytoviricota_cov, $blob[4]);
                        break;
                    default:
                        break;
                }

            }
            $Proteobacteria = array('Proteobacteria_name' => $Proteobacteria_name, 'Proteobacteria_length' => $Proteobacteria_length, 'Proteobacteria_gc' => $Proteobacteria_gc, 'Proteobacteria_cov' => $Proteobacteria_gc);
            $Chordata = array('Chordata_name' => $Chordata_name, 'Chordata_length' => $Chordata_length, 'Chordata_gc' => $Chordata_gc, 'Chordata_cov' => $Chordata_cov);
            $Cyanobacteria = array('Cyanobacteria_name' => $Cyanobacteria_name, 'Cyanobacteria_length' => $Cyanobacteria_length, 'Cyanobacteria_gc' => $Cyanobacteria_gc, 'Cyanobacteria_cov' => $Cyanobacteria_cov);
            $Actinobacteria = array('Actinobacteria_name' => $Actinobacteria_name, 'Actinobacteria_length' => $Actinobacteria_length, 'Actinobacteria_gc' => $Actinobacteria_gc, 'Actinobacteria_cov' => $Actinobacteria_cov);
            $Firmicutes = array('Firmicutes_name' => $Firmicutes_name, 'Firmicutes_length' => $Firmicutes_length, 'Firmicutes_gc' => $Firmicutes_gc, 'Firmicutes_cov' => $Firmicutes_cov);
            $Basidiomycota = array('Basidiomycota_name' => $Basidiomycota_name, 'Basidiomycota_length' => $Basidiomycota_length, 'Basidiomycota_gc' => $Basidiomycota_gc, 'Basidiomycota_cov' => $Basidiomycota_cov);
            $Nucleocytoviricota = array('Nucleocytoviricota_name' => $Nucleocytoviricota_name, 'Nucleocytoviricota_length' => $Nucleocytoviricota_length, 'Nucleocytoviricota_gc' => $Nucleocytoviricota_gc, 'Nucleocytoviricota_cov' => $Nucleocytoviricota_cov);

            $blob_picture = array('Proteobacteria' => $Proteobacteria, 'Chordata' => $Chordata, 'Cyanobacteria' => $Cyanobacteria, 'Actinobacteria' => $Actinobacteria, 'Firmicutes' => $Firmicutes, 'Basidiomycota' => $Basidiomycota, 'Nucleocytoviricota' => $Nucleocytoviricota);
            $data = array('blob_table' => $blob_table, 'blob_picture' => $blob_picture);
            return response()->json(['code' => 200, 'data' => $data]);
        }else {
            $quast_header = $quast_result =  null;
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    public function blob(Request $request)
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
        $blob_txt_path = $project_accession . '/' . $uuid .'/results/blob/' . $blob . '/' . $blob . '.blobDB.table.txt';
        if(Storage::disk('local')->exists($blob_txt_path)){
            $blob_txt = Storage::get($blob_txt_path);
            $blob_txt = explode("\n", $blob_txt);
            $blob_txt = array_splice($blob_txt, 10);
            $blob_table = array();
            foreach($blob_txt as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                array_splice($blob,6,1);
                array_splice($blob,6,1);
                array_splice($blob,7,1);
                array_splice($blob,7,1);
                array_splice($blob,8,1);
                array_splice($blob,8,1);
                array_splice($blob,9,1);
                array_splice($blob,9,1);
                array_splice($blob,10,1);
                array_splice($blob,10,1);
                array_splice($blob,11,1);
                array_splice($blob,11,1);
                array_push($blob_table,$blob);
            }
            $data = array('blob_table' => $blob_table);
            return response()->json(['code' => 200, 'data' => $data]);
        }else{
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    public function blob_pic(Request $request)
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
        $blob_pic = $request->input('blob_pic');
        $blob_pic_path = $project_accession . '/' . $uuid .'/results/blob/' . $blob_pic . '/' . $blob_pic . '.blobDB.table.txt';
        if(Storage::disk('local')->exists($blob_pic_path)){
            // blob_picture
            $blob_pic = Storage::get($blob_pic_path);
            $blob_pic = explode("\n", $blob_pic);
            $blob_pic = array_splice($blob_pic, 10);

            // gc
            $Proteobacteria_gc = array();
            $Chordata_gc = array();
            $Cyanobacteria_gc = array();
            $Actinobacteria_gc = array();
            $Firmicutes_gc = array();
            $Basidiomycota_gc = array();
            $Nucleocytoviricota_gc = array();

            // coverage
            $Proteobacteria_cov = array();
            $Chordata_cov = array();
            $Cyanobacteria_cov = array();
            $Actinobacteria_cov = array();
            $Firmicutes_cov = array();
            $Basidiomycota_cov = array();
            $Nucleocytoviricota_cov = array();

            // name
            $Proteobacteria_name = array();
            $Chordata_name = array();
            $Cyanobacteria_name = array();
            $Actinobacteria_name = array();
            $Firmicutes_name = array();
            $Basidiomycota_name = array();
            $Nucleocytoviricota_name = array();

            // length
            $Proteobacteria_length = array();
            $Chordata_length = array();
            $Cyanobacteria_length = array();
            $Actinobacteria_length = array();
            $Firmicutes_length = array();
            $Basidiomycota_length = array();
            $Nucleocytoviricota_length = array();

            foreach($blob_pic as $blob){
                $blob = explode("\t", $blob);
                $len_pos = strpos($blob[0], '_length');
                $blob[0] = substr($blob[0], 0, $len_pos);
                // phylum
                switch($blob[8])
                {
                    case 'Proteobacteria':
                        array_push($Proteobacteria_name, $blob[0]);
                        array_push($Proteobacteria_length, $blob[1]);
                        array_push($Proteobacteria_gc, $blob[2]);
                        array_push($Proteobacteria_cov, $blob[4]);
                        break;
                    case 'Chordata':
                        array_push($Chordata_name, $blob[0]);
                        array_push($Chordata_length, $blob[1]);
                        array_push($Chordata_gc, $blob[2]);
                        array_push($Chordata_cov, $blob[4]);
                        break;
                    case 'Cyanobacteria':
                        array_push($Cyanobacteria_name, $blob[0]);
                        array_push($Cyanobacteria_length, $blob[1]);
                        array_push($Cyanobacteria_gc, $blob[2]);
                        array_push($Cyanobacteria_cov, $blob[4]);
                        break;
                    case 'Actinobacteria':
                        array_push($Actinobacteria_name, $blob[0]);
                        array_push($Actinobacteria_length, $blob[1]);
                        array_push($Actinobacteria_gc, $blob[2]);
                        array_push($Actinobacteria_cov, $blob[4]);
                        break;
                    case 'Firmicutes':
                        array_push($Firmicutes_name, $blob[0]);
                        array_push($Firmicutes_length, $blob[1]);
                        array_push($Firmicutes_gc, $blob[2]);
                        array_push($Firmicutes_cov, $blob[4]);
                        break;
                    case 'Basidiomycota':
                        array_push($Basidiomycota_name, $blob[0]);
                        array_push($Basidiomycota_length, $blob[1]);
                        array_push($Basidiomycota_gc, $blob[2]);
                        array_push($Basidiomycota_cov, $blob[4]);
                        break;
                    case 'Nucleocytoviricota':
                        array_push($Nucleocytoviricota_name, $blob[0]);
                        array_push($Nucleocytoviricota_length, $blob[1]);
                        array_push($Nucleocytoviricota_gc, $blob[2]);
                        array_push($Nucleocytoviricota_cov, $blob[4]);
                        break;
                    default:
                        break;
                }
            }
            $Proteobacteria = array('Proteobacteria_name' => $Proteobacteria_name, 'Proteobacteria_length' => $Proteobacteria_length, 'Proteobacteria_gc' => $Proteobacteria_gc, 'Proteobacteria_cov' => $Proteobacteria_gc);
            $Chordata = array('Chordata_name' => $Chordata_name, 'Chordata_length' => $Chordata_length, 'Chordata_gc' => $Chordata_gc, 'Chordata_cov' => $Chordata_cov);
            $Cyanobacteria = array('Cyanobacteria_name' => $Cyanobacteria_name, 'Cyanobacteria_length' => $Cyanobacteria_length, 'Cyanobacteria_gc' => $Cyanobacteria_gc, 'Cyanobacteria_cov' => $Cyanobacteria_cov);
            $Actinobacteria = array('Actinobacteria_name' => $Actinobacteria_name, 'Actinobacteria_length' => $Actinobacteria_length, 'Actinobacteria_gc' => $Actinobacteria_gc, 'Actinobacteria_cov' => $Actinobacteria_cov);
            $Firmicutes = array('Firmicutes_name' => $Firmicutes_name, 'Firmicutes_length' => $Firmicutes_length, 'Firmicutes_gc' => $Firmicutes_gc, 'Firmicutes_cov' => $Firmicutes_cov);
            $Basidiomycota = array('Basidiomycota_name' => $Basidiomycota_name, 'Basidiomycota_length' => $Basidiomycota_length, 'Basidiomycota_gc' => $Basidiomycota_gc, 'Basidiomycota_cov' => $Basidiomycota_cov);
            $Nucleocytoviricota = array('Nucleocytoviricota_name' => $Nucleocytoviricota_name, 'Nucleocytoviricota_length' => $Nucleocytoviricota_length, 'Nucleocytoviricota_gc' => $Nucleocytoviricota_gc, 'Nucleocytoviricota_cov' => $Nucleocytoviricota_cov);
            $blob_picture = array('Proteobacteria' => $Proteobacteria, 'Chordata' => $Chordata, 'Cyanobacteria' => $Cyanobacteria, 'Actinobacteria' => $Actinobacteria, 'Firmicutes' => $Firmicutes, 'Basidiomycota' => $Basidiomycota, 'Nucleocytoviricota' => $Nucleocytoviricota);
            $data = array('blob_picture' => $blob_picture);
            return response()->json(['code' => 200, 'data' => $data]);
        }else{
            return response()->json(['code' => 404, 'data' => 'failed']);
        }
    }

    public function blob_classify(Request $request)
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
        $blob_classify = $request->input('blob_classify');
        $blob_val = $request->input('blob');
        $blob_path = $project_accession . '/' . $uuid .'/results/blob/' . $blob_val . '/' . $blob_val . '.blobDB.table.txt';
        $blob_txt = Storage::get($blob_path);
        $blob_txt = explode("\n", $blob_txt);
        $blob_txt = array_splice($blob_txt, 10);
        switch($blob_classify){
            case 'superkingdom':
                // gc
                $Bacteria_gc = array();
                $Eukaryota_gc = array();
                $Viruses_gc = array();
                // cov
                $Bacteria_cov = array();
                $Eukaryota_cov = array();
                $Viruses_cov = array();
                // name
                $Bacteria_name = array();
                $Eukaryota_name = array();
                $Viruses_name = array();
                // length
                $Bacteria_length = array();
                $Eukaryota_length = array();
                $Viruses_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[5]){
                        case 'Bacteria':
                            array_push($Bacteria_name, $blob[0]);
                            array_push($Bacteria_length, $blob[1]);
                            array_push($Bacteria_gc, $blob[2]);
                            array_push($Bacteria_cov, $blob[4]);
                            break;
                        case 'Eukaryota':
                            array_push($Eukaryota_name, $blob[0]);
                            array_push($Eukaryota_length, $blob[1]);
                            array_push($Eukaryota_gc, $blob[2]);
                            array_push($Eukaryota_cov, $blob[4]);
                            break;
                        case 'Viruses':
                            array_push($Viruses_name, $blob[0]);
                            array_push($Viruses_length, $blob[1]);
                            array_push($Viruses_gc, $blob[2]);
                            array_push($Viruses_cov, $blob[4]);
                            break;
                        default:
                            break;
                    }
                }
                $Bacteria = array('Bacteria_name' => $Bacteria_name,
                'Bacteria_length' => $Bacteria_length, 'Bacteria_gc' => $Bacteria_gc, 'Bacteria_cov' => $Bacteria_cov);
                $Eukaryota = array('Eukaryota_name' => $Eukaryota_name, 'Eukaryota_length' => $Eukaryota_length, 'Eukaryota_gc' => $Eukaryota_gc, 'Eukaryota_cov' => $Eukaryota_cov);
                $Viruses = array('Viruses_name' => $Viruses_name, 'Viruses_length' => $Viruses_length, 'Viruses_gc' => $Viruses_gc, 'Viruses_cov' => $Viruses_cov);
                $data = array('Bacteria' => $Bacteria, 'Eukaryota' => $Eukaryota, 'Viruses' => $Viruses);
                return response()->json(['code' => 200, 'data' => $data]);
            case 'phylum':
                // gc
                $Proteobacteria_gc = array();
                $Chordata_gc = array();
                $Cyanobacteria_gc = array();
                $Actinobacteria_gc = array();
                $Firmicutes_gc = array();
                $Basidiomycota_gc = array();
                $Nucleocytoviricota_gc = array();
                // cov
                $Proteobacteria_cov = array();
                $Chordata_cov = array();
                $Cyanobacteria_cov = array();
                $Actinobacteria_cov = array();
                $Firmicutes_cov = array();
                $Basidiomycota_cov = array();
                $Nucleocytoviricota_cov = array();
                // name
                $Proteobacteria_name = array();
                $Chordata_name = array();
                $Cyanobacteria_name = array();
                $Actinobacteria_name = array();
                $Firmicutes_name = array();
                $Basidiomycota_name = array();
                $Nucleocytoviricota_name = array();
                // length
                $Proteobacteria_length = array();
                $Chordata_length = array();
                $Cyanobacteria_length = array();
                $Actinobacteria_length = array();
                $Firmicutes_length = array();
                $Basidiomycota_length = array();
                $Nucleocytoviricota_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[8])
                    {
                        case 'Proteobacteria':
                            array_push($Proteobacteria_name, $blob[0]);
                            array_push($Proteobacteria_length, $blob[1]);
                            array_push($Proteobacteria_gc, $blob[2]);
                            array_push($Proteobacteria_cov, $blob[4]);
                            break;
                        case 'Chordata':
                            array_push($Chordata_name, $blob[0]);
                            array_push($Chordata_length, $blob[1]);
                            array_push($Chordata_gc, $blob[2]);
                            array_push($Chordata_cov, $blob[4]);
                            break;
                        case 'Cyanobacteria':
                            array_push($Cyanobacteria_name, $blob[0]);
                            array_push($Cyanobacteria_length, $blob[1]);
                            array_push($Cyanobacteria_gc, $blob[2]);
                            array_push($Cyanobacteria_cov, $blob[4]);
                            break;
                        case 'Actinobacteria':
                            array_push($Actinobacteria_name, $blob[0]);
                            array_push($Actinobacteria_length, $blob[1]);
                            array_push($Actinobacteria_gc, $blob[2]);
                            array_push($Actinobacteria_cov, $blob[4]);
                            break;
                        case 'Firmicutes':
                            array_push($Firmicutes_name, $blob[0]);
                            array_push($Firmicutes_length, $blob[1]);
                            array_push($Firmicutes_gc, $blob[2]);
                            array_push($Firmicutes_cov, $blob[4]);
                            break;
                        case 'Basidiomycota':
                            array_push($Basidiomycota_name, $blob[0]);
                            array_push($Basidiomycota_length, $blob[1]);
                            array_push($Basidiomycota_gc, $blob[2]);
                            array_push($Basidiomycota_cov, $blob[4]);
                            break;
                        case 'Nucleocytoviricota':
                            array_push($Nucleocytoviricota_name, $blob[0]);
                            array_push($Nucleocytoviricota_length, $blob[1]);
                            array_push($Nucleocytoviricota_gc, $blob[2]);
                            array_push($Nucleocytoviricota_cov, $blob[4]);
                            break;
                        default:
                            break;
                    }
                }
                $Proteobacteria = array('Proteobacteria_name' => $Proteobacteria_name, 'Proteobacteria_length' => $Proteobacteria_length, 'Proteobacteria_gc' => $Proteobacteria_gc, 'Proteobacteria_cov' => $Proteobacteria_gc);
                $Chordata = array('Chordata_name' => $Chordata_name, 'Chordata_length' => $Chordata_length, 'Chordata_gc' => $Chordata_gc, 'Chordata_cov' => $Chordata_cov);
                $Cyanobacteria = array('Cyanobacteria_name' => $Cyanobacteria_name, 'Cyanobacteria_length' => $Cyanobacteria_length, 'Cyanobacteria_gc' => $Cyanobacteria_gc, 'Cyanobacteria_cov' => $Cyanobacteria_cov);
                $Actinobacteria = array('Actinobacteria_name' => $Actinobacteria_name, 'Actinobacteria_length' => $Actinobacteria_length, 'Actinobacteria_gc' => $Actinobacteria_gc, 'Actinobacteria_cov' => $Actinobacteria_cov);
                $Firmicutes = array('Firmicutes_name' => $Firmicutes_name, 'Firmicutes_length' => $Firmicutes_length, 'Firmicutes_gc' => $Firmicutes_gc, 'Firmicutes_cov' => $Firmicutes_cov);
                $Basidiomycota = array('Basidiomycota_name' => $Basidiomycota_name, 'Basidiomycota_length' => $Basidiomycota_length, 'Basidiomycota_gc' => $Basidiomycota_gc, 'Basidiomycota_cov' => $Basidiomycota_cov);
                $Nucleocytoviricota = array('Nucleocytoviricota_name' => $Nucleocytoviricota_name, 'Nucleocytoviricota_length' => $Nucleocytoviricota_length, 'Nucleocytoviricota_gc' => $Nucleocytoviricota_gc, 'Nucleocytoviricota_cov' => $Nucleocytoviricota_cov);
                $blob_picture = array('Proteobacteria' => $Proteobacteria, 'Chordata' => $Chordata, 'Cyanobacteria' => $Cyanobacteria, 'Actinobacteria' => $Actinobacteria, 'Firmicutes' => $Firmicutes, 'Basidiomycota' => $Basidiomycota, 'Nucleocytoviricota' => $Nucleocytoviricota);
                $data = array('blob_picture' => $blob_picture);
                return response()->json(['code' => 200, 'data' => $data]);
            case 'order':
                // gc
                $Enterobacterales_gc = array();
                $Primates_gc = array();
                $Synechococcales_gc = array();
                $Propionibacteriales_gc = array();
                $Bacillales_gc = array();
                $Malasseziales_gc = array();
                $Campylobacterales_gc = array();
                $Imitervirales_gc = array();
                // cov
                $Enterobacterales_cov = array();
                $Primates_cov = array();
                $Synechococcales_cov = array();
                $Propionibacteriales_cov = array();
                $Bacillales_cov = array();
                $Malasseziales_cov = array();
                $Campylobacterales_cov = array();
                $Imitervirales_cov = array();
                // name
                $Enterobacterales_name = array();
                $Primates_name = array();
                $Synechococcales_name = array();
                $Propionibacteriales_name = array();
                $Bacillales_name = array();
                $Malasseziales_name = array();
                $Campylobacterales_name = array();
                $Imitervirales_name = array();
                // length
                $Enterobacterales_length = array();
                $Primates_length = array();
                $Synechococcales_length = array();
                $Propionibacteriales_length = array();
                $Bacillales_length = array();
                $Malasseziales_length = array();
                $Campylobacterales_length = array();
                $Imitervirales_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[11])
                    {
                        case 'Enterobacterales':
                            array_push($Enterobacterales_name, $blob[0]);
                            array_push($Enterobacterales_length, $blob[1]);
                            array_push($Enterobacterales_gc, $blob[2]);
                            array_push($Enterobacterales_cov, $blob[4]);
                            break;
                        case 'Primates':
                            array_push($Primates_name, $blob[0]);
                            array_push($Primates_length, $blob[1]);
                            array_push($Primates_gc, $blob[2]);
                            array_push($Primates_cov, $blob[4]);
                            break;
                        case 'Synechococcales':
                            array_push($Synechococcales_name, $blob[0]);
                            array_push($Synechococcales_length, $blob[1]);
                            array_push($Synechococcales_gc, $blob[2]);
                            array_push($Synechococcales_cov, $blob[4]);
                            break;
                        case 'Propionibacteriales':
                            array_push($Propionibacteriales_name, $blob[0]);
                            array_push($Propionibacteriales_length, $blob[1]);
                            array_push($Propionibacteriales_gc, $blob[2]);
                            array_push($Propionibacteriales_cov, $blob[4]);
                            break;
                        case 'Bacillales':
                            array_push($Bacillales_name, $blob[0]);
                            array_push($Bacillales_length, $blob[1]);
                            array_push($Bacillales_gc, $blob[2]);
                            array_push($Bacillales_cov, $blob[4]);
                            break;
                        case 'Malasseziales':
                            array_push($Malasseziales_name, $blob[0]);
                            array_push($Malasseziales_length, $blob[1]);
                            array_push($Malasseziales_gc, $blob[2]);
                            array_push($Malasseziales_cov, $blob[4]);
                            break;
                        case 'Campylobacterales':
                            array_push($Campylobacterales_name, $blob[0]);
                            array_push($Campylobacterales_length, $blob[1]);
                            array_push($Campylobacterales_gc, $blob[2]);
                            array_push($Campylobacterales_cov, $blob[4]);
                            break;
                        case 'Imitervirales':
                            array_push($Imitervirales_name, $blob[0]);
                            array_push($Imitervirales_length, $blob[1]);
                            array_push($Imitervirales_gc, $blob[2]);
                            array_push($Imitervirales_cov, $blob[4]);
                            break;
                        default:
                            break;
                    }
                }
                $Enterobacterales = array('Enterobacterales_name' => $Enterobacterales_name, 'Enterobacterales_length' => $Enterobacterales_length, 'Enterobacterales_gc' => $Enterobacterales_gc, 'Enterobacterales_cov' => $Enterobacterales_cov);
                $Primates = array('Primates_name' => $Primates_name, 'Primates_length' => $Primates_length, 'Primates_gc' => $Primates_gc, 'Primates_cov' => $Primates_cov);
                $Synechococcales = array('Synechococcales_name' => $Synechococcales_name, 'Synechococcales_length' => $Synechococcales_length, 'Synechococcales_gc' => $Synechococcales_gc, 'Synechococcales_cov' => $Synechococcales_cov);
                $Propionibacteriales = array('Propionibacteriales_name' => $Propionibacteriales_name, 'Propionibacteriales_length' => $Propionibacteriales_length, 'Propionibacteriales_gc' => $Propionibacteriales_gc, 'Propionibacteriales_cov' => $Propionibacteriales_cov);
                $Bacillales = array('Bacillales_name' => $Bacillales_name, 'Bacillales_length' => $Bacillales_length, 'Bacillales_gc' => $Bacillales_gc, 'Bacillales_cov' => $Bacillales_cov);
                $Malasseziales = array('Malasseziales_name' => $Malasseziales_name, 'Malasseziales_length' => $Malasseziales_length, 'Malasseziales_gc' => $Malasseziales_gc, 'Malasseziales_cov' => $Malasseziales_cov);
                $Campylobacterales = array('Campylobacterales_name' => $Campylobacterales_name, 'Campylobacterales_length' => $Campylobacterales_length, 'Campylobacterales_gc' => $Campylobacterales_gc, 'Campylobacterales_cov' => $Campylobacterales_cov);
                $Imitervirales = array('Imitervirales_name' => $Imitervirales_name, 'Imitervirales_length' => $Imitervirales_length, 'Imitervirales_gc' => $Imitervirales_gc, 'Imitervirales_cov' => $Imitervirales_cov);
                $data = array('Enterobacterales' => $Enterobacterales, 'Primates' => $Primates, 'Synechococcales' => $Synechococcales,'Propionibacteriales' => $Propionibacteriales,'Bacillales' => $Bacillales,'Malasseziales' => $Malasseziales, 'Campylobacterales' => $Campylobacterales, 'Imitervirales' => $Imitervirales);
                return response()->json(['code' => 200, 'data' => $data]);
            case 'family':
                // gc
                $Enterobacteriaceae_gc = array();
                $Hominidae_gc = array();
                $Staphylococcaceae_gc = array();
                $Moraxellaceae_gc = array();
                $Propionibacteriaceae_gc = array();
                $Malasseziaceae_gc = array();
                $Synechococcaceae_gc = array();
                $Mimiviridae_gc = array();
                $Helicobacteraceae_gc = array();
                $Rhizobiaceae_gc = array();
                $Cryptosporidiidae_gc = array();
                // cov
                $Enterobacteriaceae_cov = array();
                $Hominidae_cov = array();
                $Staphylococcaceae_cov = array();
                $Moraxellaceae_cov = array();
                $Propionibacteriaceae_cov = array();
                $Malasseziaceae_cov = array();
                $Synechococcaceae_cov = array();
                $Mimiviridae_cov = array();
                $Helicobacteraceae_cov = array();
                $Rhizobiaceae_cov = array();
                $Cryptosporidiidae_cov = array();
                // name
                $Enterobacteriaceae_name = array();
                $Hominidae_name = array();
                $Staphylococcaceae_name = array();
                $Moraxellaceae_name = array();
                $Propionibacteriaceae_name = array();
                $Malasseziaceae_name = array();
                $Synechococcaceae_name = array();
                $Mimiviridae_name = array();
                $Helicobacteraceae_name = array();
                $Rhizobiaceae_name = array();
                $Cryptosporidiidae_name = array();
                // length
                $Enterobacteriaceae_length = array();
                $Hominidae_length = array();
                $Staphylococcaceae_length = array();
                $Moraxellaceae_length = array();
                $Propionibacteriaceae_length = array();
                $Malasseziaceae_length = array();
                $Synechococcaceae_length = array();
                $Mimiviridae_length = array();
                $Helicobacteraceae_length = array();
                $Rhizobiaceae_length = array();
                $Cryptosporidiidae_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[14])
                    {
                        case 'Enterobacteriaceae':
                            array_push($Enterobacteriaceae_name, $blob[0]);
                            array_push($Enterobacteriaceae_length, $blob[1]);
                            array_push($Enterobacteriaceae_gc, $blob[2]);
                            array_push($Enterobacteriaceae_cov, $blob[4]);
                            break;
                        case 'Hominidae':
                            array_push($Hominidae_name, $blob[0]);
                            array_push($Hominidae_length, $blob[1]);
                            array_push($Hominidae_gc, $blob[2]);
                            array_push($Hominidae_cov, $blob[4]);
                            break;
                        case 'Staphylococcaceae':
                            array_push($Staphylococcaceae_name, $blob[0]);
                            array_push($Staphylococcaceae_length, $blob[1]);
                            array_push($Staphylococcaceae_gc, $blob[2]);
                            array_push($Staphylococcaceae_cov, $blob[4]);
                            break;
                        case 'Moraxellaceae':
                            array_push($Moraxellaceae_name, $blob[0]);
                            array_push($Moraxellaceae_length, $blob[1]);
                            array_push($Moraxellaceae_gc, $blob[2]);
                            array_push($Moraxellaceae_cov, $blob[4]);
                            break;
                        case 'Propionibacteriaceae':
                            array_push($Propionibacteriaceae_name, $blob[0]);
                            array_push($Propionibacteriaceae_length, $blob[1]);
                            array_push($Propionibacteriaceae_gc, $blob[2]);
                            array_push($Propionibacteriaceae_cov, $blob[4]);
                            break;
                        case 'Malasseziaceae':
                            array_push($Malasseziaceae_name, $blob[0]);
                            array_push($Malasseziaceae_length, $blob[1]);
                            array_push($Malasseziaceae_gc, $blob[2]);
                            array_push($Malasseziaceae_cov, $blob[4]);
                            break;
                        case 'Synechococcaceae':
                            array_push($Synechococcaceae_name, $blob[0]);
                            array_push($Synechococcaceae_length, $blob[1]);
                            array_push($Synechococcaceae_gc, $blob[2]);
                            array_push($Synechococcaceae_cov, $blob[4]);
                            break;
                        case 'Mimiviridae':
                            array_push($Mimiviridae_name, $blob[0]);
                            array_push($Mimiviridae_length, $blob[1]);
                            array_push($Mimiviridae_gc, $blob[2]);
                            array_push($Mimiviridae_cov, $blob[4]);
                            break;
                        case 'Helicobacteraceae':
                            array_push($Helicobacteraceae_name, $blob[0]);
                            array_push($Helicobacteraceae_length, $blob[1]);
                            array_push($Helicobacteraceae_gc, $blob[2]);
                            array_push($Helicobacteraceae_cov, $blob[4]);
                        case 'Rhizobiaceae':
                            array_push($Rhizobiaceae_name, $blob[0]);
                            array_push($Rhizobiaceae_length, $blob[1]);
                            array_push($Rhizobiaceae_gc, $blob[2]);
                            array_push($Rhizobiaceae_cov, $blob[4]);
                        case 'Cryptosporidiidae':
                            array_push($Cryptosporidiidae_name, $blob[0]);
                            array_push($Cryptosporidiidae_length, $blob[1]);
                            array_push($Cryptosporidiidae_gc, $blob[2]);
                            array_push($Cryptosporidiidae_cov, $blob[4]);
                        default:
                            break;
                    }
                }
                $Enterobacteriaceae = array('Enterobacteriaceae_name' => $Enterobacteriaceae_name, 'Enterobacteriaceae_length' => $Enterobacteriaceae_length, 'Enterobacteriaceae_gc' => $Enterobacteriaceae_gc, 'Enterobacteriaceae_cov' => $Enterobacteriaceae_cov);
                $Hominidae = array('Hominidae_name' => $Hominidae_name, 'Hominidae_length' => $Hominidae_length, 'Hominidae_gc' => $Hominidae_gc, 'Hominidae_cov' => $Hominidae_cov);
                $Staphylococcaceae = array('Staphylococcaceae_name' => $Staphylococcaceae_name, 'Staphylococcaceae_length' => $Staphylococcaceae_length, 'Staphylococcaceae_gc' => $Staphylococcaceae_gc, 'Staphylococcaceae_cov' => $Staphylococcaceae_cov);
                $Moraxellaceae = array('Moraxellaceae_name' => $Moraxellaceae_name, 'Moraxellaceae_length' => $Moraxellaceae_length, 'Moraxellaceae_gc' => $Moraxellaceae_gc, 'Moraxellaceae_cov' => $Moraxellaceae_cov);
                $Propionibacteriaceae = array('Propionibacteriaceae_name' => $Propionibacteriaceae_name, 'Propionibacteriaceae_length' => $Propionibacteriaceae_length, 'Propionibacteriaceae_gc' => $Propionibacteriaceae_gc, 'Propionibacteriaceae_cov' => $Propionibacteriaceae_cov);
                $Malasseziaceae = array('Malasseziaceae_name' => $Malasseziaceae_name, 'Malasseziaceae_length' => $Malasseziaceae_length, 'Malasseziaceae_gc' => $Malasseziaceae_gc, 'Malasseziaceae_cov' => $Malasseziaceae_cov);
                $Synechococcaceae = array('Synechococcaceae_name' => $Synechococcaceae_name, 'Synechococcaceae_length' => $Synechococcaceae_length, 'Synechococcaceae_gc' => $Synechococcaceae_gc, 'Synechococcaceae_cov' => $Synechococcaceae_cov);
                $Mimiviridae = array('Mimiviridae_name' => $Mimiviridae_name, 'Mimiviridae_length' => $Mimiviridae_length, 'Mimiviridae_gc' => $Mimiviridae_gc, 'Mimiviridae_cov' => $Mimiviridae_cov);
                $Helicobacteraceae = array('Helicobacteraceae_name' => $Helicobacteraceae_name, 'Helicobacteraceae_length' => $Helicobacteraceae_length, 'Helicobacteraceae_gc' => $Helicobacteraceae_gc, 'Helicobacteraceae_cov' => $Helicobacteraceae_cov);
                $Rhizobiaceae = array('Rhizobiaceae_name' => $Rhizobiaceae_name, 'Rhizobiaceae_length' => $Rhizobiaceae_length, 'Rhizobiaceae_gc' => $Rhizobiaceae_gc, 'Rhizobiaceae_cov' => $Rhizobiaceae_cov);
                $Cryptosporidiidae = array('Cryptosporidiidae_name' => $Cryptosporidiidae_name, 'Cryptosporidiidae_length' => $Cryptosporidiidae_length, 'Cryptosporidiidae_gc' => $Cryptosporidiidae_gc, 'Cryptosporidiidae_cov' => $Cryptosporidiidae_cov);
                $data = array('Enterobacteriaceae' => $Enterobacteriaceae, 'Hominidae' => $Hominidae, 'Staphylococcaceae' => $Staphylococcaceae, 'Moraxellaceae' => $Moraxellaceae, 'Propionibacteriaceae' => $Propionibacteriaceae, 'Malasseziaceae' => $Malasseziaceae, 'Synechococcaceae' => $Synechococcaceae, 'Mimiviridae' => $Mimiviridae, 'Helicobacteraceae' => $Helicobacteraceae, 'Rhizobiaceae' => $Rhizobiaceae, 'Cryptosporidiidae' => $Cryptosporidiidae);
                return response()->json(['code' => 200, 'data' => $data]);
            case 'genus':
                // gc
                $Escherichia_gc = array();
                $Helicobacter_gc = array();
                $Homo_gc = array();
                $Synechococcus_gc = array();
                $Cutibacterium_gc = array();
                $Moraxella_gc = array();
                $Malassezia_gc = array();
                $Mimiviridae_undef_gc = array();
                $Shigella_gc = array();
                // cov
                $Escherichia_cov = array();
                $Helicobacter_cov = array();
                $Homo_cov = array();
                $Synechococcus_cov = array();
                $Cutibacterium_cov = array();
                $Moraxella_cov = array();
                $Malassezia_cov = array();
                $Mimiviridae_undef_cov = array();
                $Shigella_cov = array();
                // name
                $Escherichia_name = array();
                $Helicobacter_name = array();
                $Homo_name = array();
                $Synechococcus_name = array();
                $Cutibacterium_name = array();
                $Moraxella_name = array();
                $Malassezia_name = array();
                $Mimiviridae_undef_name = array();
                $Shigella_name = array();
                // length
                $Escherichia_length = array();
                $Helicobacter_length = array();
                $Homo_length = array();
                $Synechococcus_length = array();
                $Cutibacterium_length = array();
                $Moraxella_length = array();
                $Malassezia_length = array();
                $Mimiviridae_undef_length = array();
                $Shigella_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[17])
                    {
                        case 'Escherichia':
                            array_push($Escherichia_name, $blob[0]);
                            array_push($Escherichia_length, $blob[1]);
                            array_push($Escherichia_gc, $blob[2]);
                            array_push($Escherichia_cov, $blob[4]);
                            break;
                        case 'Helicobacter':
                            array_push($Helicobacter_name, $blob[0]);
                            array_push($Helicobacter_length, $blob[1]);
                            array_push($Helicobacter_gc, $blob[2]);
                            array_push($Helicobacter_cov, $blob[4]);
                            break;
                        case 'Homo':
                            array_push($Homo_name, $blob[0]);
                            array_push($Homo_length, $blob[1]);
                            array_push($Homo_gc, $blob[2]);
                            array_push($Homo_cov, $blob[4]);
                            break;
                        case 'Synechococcus':
                            array_push($Synechococcus_name, $blob[0]);
                            array_push($Synechococcus_length, $blob[1]);
                            array_push($Synechococcus_gc, $blob[2]);
                            array_push($Synechococcus_cov, $blob[4]);
                            break;
                        case 'Cutibacterium':
                            array_push($Cutibacterium_name, $blob[0]);
                            array_push($Cutibacterium_length, $blob[1]);
                            array_push($Cutibacterium_gc, $blob[2]);
                            array_push($Cutibacterium_cov, $blob[4]);
                            break;
                        case 'Moraxella':
                            array_push($Moraxella_name, $blob[0]);
                            array_push($Moraxella_length, $blob[1]);
                            array_push($Moraxella_gc, $blob[2]);
                            array_push($Moraxella_cov, $blob[4]);
                            break;
                        case 'Malassezia':
                            array_push($Malassezia_name, $blob[0]);
                            array_push($Malassezia_length, $blob[1]);
                            array_push($Malassezia_gc, $blob[2]);
                            array_push($Malassezia_cov, $blob[4]);
                            break;
                        case 'Mimiviridae undef':
                            array_push($Mimiviridae_undef_name, $blob[0]);
                            array_push($Mimiviridae_undef_length, $blob[1]);
                            array_push($Mimiviridae_undef_gc, $blob[2]);
                            array_push($Mimiviridae_undef_cov, $blob[4]);
                            break;
                        case 'Shigella':
                            array_push($Shigella_name, $blob[0]);
                            array_push($Shigella_length, $blob[1]);
                            array_push($Shigella_gc, $blob[2]);
                            array_push($Shigella_cov, $blob[4]);
                        default:
                            break;
                    }
                }
                $Escherichia = array('Escherichia_name' => $Escherichia_name, 'Escherichia_length' => $Escherichia_length, 'Escherichia_gc' => $Escherichia_gc, 'Escherichia_cov' => $Escherichia_cov);
                $Helicobacter = array('Helicobacter_name' => $Helicobacter_name, 'Helicobacter_length' => $Helicobacter_length, 'Helicobacter_gc' => $Helicobacter_gc, 'Helicobacter_cov' => $Helicobacter_cov);
                $Homo = array('Homo_name' => $Homo_name, 'Homo_length' => $Homo_length, 'Homo_gc' => $Homo_gc, 'Homo_cov' => $Homo_cov);
                $Synechococcus = array('Synechococcus_name' => $Synechococcus_name, 'Synechococcus_length' => $Synechococcus_length, 'Synechococcus_gc' => $Synechococcus_gc, 'Synechococcus_cov' => $Synechococcus_cov);
                $Cutibacterium = array('Cutibacterium_name' => $Cutibacterium_name, 'Cutibacterium_length' => $Cutibacterium_length, 'Cutibacterium_gc' => $Cutibacterium_gc, 'Cutibacterium_cov' => $Cutibacterium_cov);
                $Moraxella = array('Moraxella_name' => $Moraxella_name, 'Moraxella_length' => $Moraxella_length, 'Moraxella_gc' => $Moraxella_gc, 'Moraxella_cov' => $Moraxella_cov);
                $Malassezia = array('Malassezia_name' => $Malassezia_name, 'Malassezia_length' => $Malassezia_length, 'Malassezia_gc' => $Malassezia_gc, 'Malassezia_cov' => $Malassezia_cov);
                $Mimiviridae_undef = array('Mimiviridae_undef_name' => $Mimiviridae_undef_name, 'Mimiviridae_undef_length' => $Mimiviridae_undef_length, 'Mimiviridae_undef_gc' => $Mimiviridae_undef_gc, 'Mimiviridae_undef_cov' => $Mimiviridae_undef_cov);
                $Shigella = array('Shigella_name' => $Shigella_name, 'Shigella_length' => $Shigella_length, 'Shigella_gc' => $Shigella_gc, 'Shigella_cov' => $Shigella_cov);
                $data = array('Escherichia' => $Escherichia, 'Helicobacter' => $Helicobacter, 'Homo' => $Homo, 'Synechococcus' => $Synechococcus, 'Cutibacterium' => $Cutibacterium, 'Moraxella' => $Moraxella, 'Malassezia' => $Malassezia, 'Mimiviridae_undef' => $Mimiviridae_undef, 'Shigella' => $Shigella);
                return response()->json(['code' => 200, 'data' => $data]);
            case 'species':
                // gc
                $Escherichia_coli_gc = array();
                $Homo_sapiens_gc = array();
                $Synechococcus_sp_UTEX_gc = array();
                $Cutibacterium_acnes_gc = array();
                $Staphylococcus_epidermidis_gc = array();
                $Helicobacter_pylori_gc = array();
                $Shigella_flexneri_gc = array();
                $Shigella_sonnei_gc = array();
                $Malassezia_restricta_gc = array();
                $eukaryotic_synthetic_construct_gc = array();
                $Synechococcus_elongatus_gc = array();
                $Pan_troglodytes_gc = array();
                $Haloarcula_hispanica_gc = array();
                // cov
                $Escherichia_coli_cov = array();
                $Homo_sapiens_cov = array();
                $Synechococcus_sp_UTEX_cov = array();
                $Cutibacterium_acnes_cov = array();
                $Staphylococcus_epidermidis_cov = array();
                $Helicobacter_pylori_cov = array();
                $Shigella_flexneri_cov = array();
                $Shigella_sonnei_cov = array();
                $Malassezia_restricta_cov = array();
                $eukaryotic_synthetic_construct_cov = array();
                $Synechococcus_elongatus_cov = array();
                $Pan_troglodytes_cov = array();
                $Haloarcula_hispanica_cov = array();
                // name
                $Escherichia_coli_name = array();
                $Homo_sapiens_name = array();
                $Synechococcus_sp_UTEX_name = array();
                $Cutibacterium_acnes_name = array();
                $Staphylococcus_epidermidis_name = array();
                $Helicobacter_pylori_name = array();
                $Shigella_flexneri_name = array();
                $Shigella_sonnei_name = array();
                $Malassezia_restricta_name = array();
                $eukaryotic_synthetic_construct_name = array();
                $Synechococcus_elongatus_name = array();
                $Pan_troglodytes_name = array();
                $Haloarcula_hispanica_name = array();
                // length
                $Escherichia_coli_length = array();
                $Homo_sapiens_length = array();
                $Synechococcus_sp_UTEX_length = array();
                $Cutibacterium_acnes_length = array();
                $Staphylococcus_epidermidis_length = array();
                $Helicobacter_pylori_length = array();
                $Shigella_flexneri_length = array();
                $Shigella_sonnei_length = array();
                $Malassezia_restricta_length = array();
                $eukaryotic_synthetic_construct_length = array();
                $Synechococcus_elongatus_length = array();
                $Pan_troglodytes_length = array();
                $Haloarcula_hispanica_length = array();
                foreach($blob_txt as $blob){
                    $blob = explode("\t", $blob);
                    $len_pos = strpos($blob[0], '_length');
                    $blob[0] = substr($blob[0], 0, $len_pos);
                    switch($blob[20])
                    {
                        case 'Escherichia coli':
                            array_push($Escherichia_coli_name, $blob[0]);
                            array_push($Escherichia_coli_length, $blob[1]);
                            array_push($Escherichia_coli_gc, $blob[2]);
                            array_push($Escherichia_coli_cov, $blob[4]);
                            break;
                        case 'Homo sapiens':
                            array_push($Homo_sapiens_name, $blob[0]);
                            array_push($Homo_sapiens_length, $blob[1]);
                            array_push($Homo_sapiens_gc, $blob[2]);
                            array_push($Homo_sapiens_cov, $blob[4]);
                            break;
                        case 'Synechococcus sp. UTEX':
                            array_push($Synechococcus_sp_UTEX_name, $blob[0]);
                            array_push($Synechococcus_sp_UTEX_length, $blob[1]);
                            array_push($Synechococcus_sp_UTEX_gc, $blob[2]);
                            array_push($Synechococcus_sp_UTEX_cov, $blob[4]);
                            break;
                        case 'Cutibacterium acnes':
                            array_push($Cutibacterium_acnes_name, $blob[0]);
                            array_push($Cutibacterium_acnes_length, $blob[1]);
                            array_push($Cutibacterium_acnes_gc, $blob[2]);
                            array_push($Cutibacterium_acnes_cov, $blob[4]);
                            break;
                        case 'Staphylococcus epidermidis':
                            array_push($Staphylococcus_epidermidis_name, $blob[0]);
                            array_push($Staphylococcus_epidermidis_length, $blob[1]);
                            array_push($Staphylococcus_epidermidis_gc, $blob[2]);
                            array_push($Staphylococcus_epidermidis_cov, $blob[4]);
                            break;
                        case 'Helicobacter pylori':
                            array_push($Helicobacter_pylori_name, $blob[0]);
                            array_push($Helicobacter_pylori_length, $blob[1]);
                            array_push($Helicobacter_pylori_gc, $blob[2]);
                            array_push($Helicobacter_pylori_cov, $blob[4]);
                            break;
                        case 'Shigella flexneri':
                            array_push($Shigella_flexneri_name, $blob[0]);
                            array_push($Shigella_flexneri_length, $blob[1]);
                            array_push($Shigella_flexneri_gc, $blob[2]);
                            array_push($Shigella_flexneri_cov, $blob[4]);
                            break;
                        case 'Shigella sonnei':
                            array_push($Shigella_sonnei_name, $blob[0]);
                            array_push($Shigella_sonnei_length, $blob[1]);
                            array_push($Shigella_sonnei_gc, $blob[2]);
                            array_push($Shigella_sonnei_cov, $blob[4]);
                            break;
                        case 'Malassezia restricta':
                            array_push($Malassezia_restricta_name, $blob[0]);
                            array_push($Malassezia_restricta_length, $blob[1]);
                            array_push($Malassezia_restricta_gc, $blob[2]);
                            array_push($Malassezia_restricta_cov, $blob[4]);
                        case 'eukaryotic synthetic construct':
                            array_push($eukaryotic_synthetic_construct_name, $blob[0]);
                            array_push($eukaryotic_synthetic_construct_length, $blob[1]);
                            array_push($eukaryotic_synthetic_construct_gc, $blob[2]);
                            array_push($eukaryotic_synthetic_construct_cov, $blob[4]);
                        case 'Synechococcus elongatus':
                            array_push($Synechococcus_elongatus_name, $blob[0]);
                            array_push($Synechococcus_elongatus_length, $blob[1]);
                            array_push($Synechococcus_elongatus_gc, $blob[2]);
                            array_push($Synechococcus_elongatus_cov, $blob[4]);
                        case 'Pan troglodytes':
                            array_push($Pan_troglodytes_name, $blob[0]);
                            array_push($Pan_troglodytes_length, $blob[1]);
                            array_push($Pan_troglodytes_gc, $blob[2]);
                            array_push($Pan_troglodytes_cov, $blob[4]);
                        case 'Haloarcula hispanica':
                            array_push($Haloarcula_hispanica_name, $blob[0]);
                            array_push($Haloarcula_hispanica_length, $blob[1]);
                            array_push($Haloarcula_hispanica_gc, $blob[2]);
                            array_push($Haloarcula_hispanica_cov, $blob[4]);
                        default:
                            break;
                    }
                }
                $Escherichia_coli = array('Escherichia_coli_name' => $Escherichia_coli_name, 'Escherichia_coli_length' => $Escherichia_coli_length, 'Escherichia_coli_gc' => $Escherichia_coli_gc, 'Escherichia_coli_cov' => $Escherichia_coli_cov);
                $Homo_sapiens = array('Homo_sapiens_name' => $Homo_sapiens_name, 'Homo_sapiens_length' => $Homo_sapiens_length, 'Homo_sapiens_gc' => $Homo_sapiens_gc, 'Homo_sapiens_cov' => $Homo_sapiens_cov);
                $Synechococcus_sp_UTEX = array('Synechococcus_sp_UTEX_name' => $Synechococcus_sp_UTEX_name, 'Synechococcus_sp_UTEX_length' => $Synechococcus_sp_UTEX_length, 'Synechococcus_sp_UTEX_gc' => $Synechococcus_sp_UTEX_gc, 'Synechococcus_sp_UTEX_cov' => $Synechococcus_sp_UTEX_cov);
                $Cutibacterium_acnes = array('Cutibacterium_acnes_name' => $Cutibacterium_acnes_name, 'Cutibacterium_acnes_length' => $Cutibacterium_acnes_length, 'Cutibacterium_acnes_gc' => $Cutibacterium_acnes_gc, 'Cutibacterium_acnes_cov' => $Cutibacterium_acnes_cov);
                $Staphylococcus_epidermidis = array('Staphylococcus_epidermidis_name' => $Staphylococcus_epidermidis_name, 'Staphylococcus_epidermidis_length' => $Staphylococcus_epidermidis_length, 'Staphylococcus_epidermidis_gc' => $Staphylococcus_epidermidis_gc, 'Staphylococcus_epidermidis_cov' => $Staphylococcus_epidermidis_cov);
                $Helicobacter_pylori = array('Helicobacter_pylori_name' => $Helicobacter_pylori_name, 'Helicobacter_pylori_length' => $Helicobacter_pylori_length, 'Helicobacter_pylori_gc' => $Helicobacter_pylori_gc, 'Helicobacter_pylori_cov' => $Helicobacter_pylori_cov);
                $Shigella_flexneri = array('Shigella_flexneri_name' => $Shigella_flexneri_name, 'Shigella_flexneri_length' => $Shigella_flexneri_length, 'Shigella_flexneri_gc' => $Shigella_flexneri_gc, 'Shigella_flexneri_cov' => $Shigella_flexneri_cov);
                $Shigella_sonnei = array('Shigella_sonnei_name' => $Shigella_sonnei_name, 'Shigella_sonnei_length' => $Shigella_sonnei_length, 'Shigella_sonnei_gc' => $Shigella_sonnei_gc, 'Shigella_sonnei_cov' => $Shigella_sonnei_cov);
                $Malassezia_restricta = array('Malassezia_restricta_name' => $Malassezia_restricta_name, 'Malassezia_restricta_length' => $Malassezia_restricta_length, 'Malassezia_restricta_gc' => $Malassezia_restricta_gc, 'Malassezia_restricta_cov' => $Malassezia_restricta_cov);
                $eukaryotic_synthetic_construct = array('eukaryotic_synthetic_construct_name' => $eukaryotic_synthetic_construct_name, 'eukaryotic_synthetic_construct_length' => $eukaryotic_synthetic_construct_length, 'eukaryotic_synthetic_construct_gc' => $eukaryotic_synthetic_construct_gc, 'eukaryotic_synthetic_construct_cov' => $eukaryotic_synthetic_construct_cov);
                $Synechococcus_elongatus = array('Synechococcus_elongatus_name' => $Synechococcus_elongatus_name, 'Synechococcus_elongatus_length' => $Synechococcus_elongatus_length, 'Synechococcus_elongatus_gc' => $Synechococcus_elongatus_gc, 'Synechococcus_elongatus_cov' => $Synechococcus_elongatus_cov);
                $Pan_troglodytes = array('Pan_troglodytes_name' => $Pan_troglodytes_name, 'Pan_troglodytes_length' => $Pan_troglodytes_length, 'Pan_troglodytes_gc' => $Pan_troglodytes_gc, 'Pan_troglodytes_cov' => $Pan_troglodytes_cov);
                $Haloarcula_hispanica = array('Haloarcula_hispanica_name' => $Haloarcula_hispanica_name, 'Haloarcula_hispanica_length' => $Haloarcula_hispanica_length, 'Haloarcula_hispanica_gc' => $Haloarcula_hispanica_gc, 'Haloarcula_hispanica_cov' => $Haloarcula_hispanica_cov);
                $data = array('Escherichia_coli' => $Escherichia_coli, 'Homo_sapiens' => $Homo_sapiens, 'Synechococcus_sp_UTEX' => $Synechococcus_sp_UTEX, 'Cutibacterium_acnes' => $Cutibacterium_acnes, 'Staphylococcus_epidermidis' => $Staphylococcus_epidermidis, 'Helicobacter_pylori' => $Helicobacter_pylori, 'Shigella_flexneri' => $Shigella_flexneri,'Shigella_sonnei' => $Shigella_sonnei, 'Malassezia_restricta' => $Malassezia_restricta, 'eukaryotic_synthetic_construct' => $eukaryotic_synthetic_construct, 'Synechococcus_elongatus' => $Synechococcus_elongatus,'Pan_troglodytes' => $Pan_troglodytes, 'Haloarcula_hispanica' => $Haloarcula_hispanica);
                return response()->json(['code' => 200, 'data' => $data]);
        }
    }

}
