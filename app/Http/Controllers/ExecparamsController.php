<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Execparams;
use App\pipelineParams;
use App\Samples;
use App\Species;
use App\Jobs;
use App\Jobs\RunPipeline;

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            /**
             * 接收表单post的数据
             */
            $samples = new Samples();
            $pipelineParams = pipelineParams::find(1);
            $sample_id = $request->input('sampleID');
            $ass = $request->input('ass') == 'ass' ? true : false;
            $snv = $request->input('snv') == 'snv' ? true : false;
            $cnv = $request->input('cnv') == 'cnv' ? true : false;
            $bulk = $request->input('bulk') == 'bulk' ? true : false;
            $saturation = $request->input('saturation') == 'saturation' ? true : false;
            $acquired = $request->input('acquired') == 'acquired' ? true : false;
            $saveTrimmed = $request->input('saveTrimmed') == 'saveTrimmed' ? true : false;
            $saveAlignedIntermediates = $request->input('saveAlignedIntermediates') == 'saveAlignedIntermediates' ? true : false;
            $genus = $request->input('genus') == 'genus' ? true : false;
            $resfinder_db = $request->input('resfinder_db') == 'resfinder_db' ? true : false;
            $nt_db = $request->input('nt_db') == 'nt_db' ? true : false;
            $eggnog = $request->input('eggnog_db') == 'eggnog_db' ? true : false;
            $kraken_db = $request->input('kraken_db') == 'kraken_db' ? true : false;
            $kofam_profile = $request->input('kofam_profile') == 'kofam_profile' ? true : false;
            $kofam_kolist = $request->input('kofam_kolist') == 'kofam_kolist' ? true : false;
            if ($request->input('genus') != null) {
                $this->validate($request, [
                    'genus_name' => 'required|max:200'
                ]);
                $genus_name = $request->input('genus_name');
            } else {
                $genus_name = null;
            }
            /**
             * 判断execparams表中是否有该sample运行的参数，如果没有就添加记录，如果有就修改记录
             */
            if (Execparams::where('samples_id', $sample_id)->get()->count() == 0) {
                Execparams::create([
                    'samples_id' => $sample_id,
                    'ass' => $ass,
                    'snv' => $snv,
                    'cnv' => $cnv,
                    'bulk' => $bulk,
                    'saturation' => $saturation,
                    'acquired' => $acquired,
                    'saveTrimmed' => $saveTrimmed,
                    'saveAlignedIntermediates' => $saveAlignedIntermediates,
                    'genus' => $genus,
                    'genus_name' => $genus_name,
                    'resfinder_db' => $resfinder_db,
                    'nt_db' => $nt_db,
                    'eggnog' => $eggnog,
                    'kraken_db' => $kraken_db,
                    'kofam_profile' => $kofam_profile,
                    'kofam_kolist' => $kofam_kolist
                ]);
            } else {
                $id = Execparams::where('samples_id', $sample_id)->value('id');
                $execparams = Execparams::find($id);
                $execparams->ass = $ass;
                $execparams->snv = $snv;
                $execparams->cnv = $cnv;
                $execparams->bulk = $bulk;
                $execparams->saturation = $saturation;
                $execparams->acquired = $acquired;
                $execparams->saveTrimmed = $saveTrimmed;
                $execparams->saveAlignedIntermediates = $saveAlignedIntermediates;
                $execparams->genus = $genus;
                $execparams->genus_name = $genus_name;
                $execparams->resfinder_db = $resfinder_db;
                $execparams->kraken_db = $kraken_db;
                $execparams->nt_db = $nt_db;
                $execparams->eggnog = $eggnog;
                $execparams->kofam_profile = $kofam_profile;
                $execparams->kofam_kolist = $kofam_kolist;
                $execparams->save();
            }

            /**
             * execparams参数表读取，拼接command
             */
            $execparams = new Execparams();
            $run_sample = $execparams->where('samples_id', $sample_id);
            $ass = $run_sample->value('ass') ? '--ass ' : '';
            $cnv = $run_sample->value('cnv') ? '--cnv ' : '';
            $snv = $run_sample->value('snv') ? '--snv ' : '';
            $bulk = $run_sample->value('bulk') ? '--bulk ' : '';
            $saturation = $run_sample->value('saturation') ? '--saturation ' : '';
            $acquired = $run_sample->value('acquired') ? '--acquired ' : '';
            $saveTrimmed = $run_sample->value('saveTrimmed') ? '--saveTrimmed ' : '';
            $saveAlignedIntermediates = $run_sample->value('saveAlignedIntermediates') ? '--saveAlignedIntermediates ' : '';
            if ($run_sample->value('genus')) {
                $genus_name = $run_sample->value('genus_name');
                $genus = '--genus ' . $genus_name . ' ';
            } else {
                $genus = '';
            }
            $pipeline_params = pipelineParams::find(1);
            $resfinder_db_path = $pipeline_params->resfinder_db_path;
            $nt_db_path = $pipeline_params->nt_db_path;
            $eggnog_db_path = $pipeline_params->eggnog_db_path;
            $kraken_db_path = $pipeline_params->kraken_db_path;
            $kofam_profile_path = $pipeline_params->kofam_profile_path;
            $kofam_kolist_path = $pipeline_params->kofam_kolist_path;

            $resfinder_db = $run_sample->value('resfinder_db') ? '--resfinder_db ' . $resfinder_db_path . ' ' : '';
            $nt_db = $run_sample->value('nt_db') ? '--nt_db ' . $nt_db_path . ' ' : '';
            $kraken_db = $run_sample->value('kraken_db') ? '--kraken_db ' . $kraken_db_path . ' ' : '';
            $eggnog_db = $run_sample->value('eggnog') ? '--eggnog_db ' . $eggnog_db_path . ' ' : '';
            $kofam_profile = $run_sample->value('kofam_profile') ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
            $kofam_kolist = $run_sample->value('kofam_kolist') ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';

            $species_id = Samples::where('id', $sample_id)->value('species_id');
            $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
            if (isset($species_id)) {
                $fasta_path = Species::where('id', $species_id)->value('fasta');
                $gff_path = Species::where('id', $species_id)->value('gff');
                $fasta_path = $base_path . '' . $fasta_path;
                $gff_path = $base_path . '' . $gff_path;
                $fasta = '--fasta ' . $fasta_path . ' ';
                $gff = '--gff ' . $gff_path . ' ';
            } else {
                $fasta = $gff = '';
            }

            $sample = Samples::find($sample_id);
            $filename1 = $sample->filename1;
            $filename1 = $base_path . '' . $filename1;

            $sample->pairends ? $filename2 = $sample->filename2 : $filename2 = null;
            preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $filename1, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename1, $file_postfix);
            $filename = str_replace($file_prefix, '*', $filename1);
            $replace_num_position = strrpos($filename, '1');
            $filename = substr_replace($filename, '[1,2]', $replace_num_position, 1);
            $filename = $base_path . '' . $filename;

            //保存目录格式 : 用户名 + 物种名(sampleLabel)
            $sample_label = Samples::where('id', $sample_id)->value('sampleLabel');
            $sample_user_name = Auth::user()->name;

            if ($filename2 != null) {
                //pairEnds
                $cmd = '/mnt/scc8t/zhousq/nextflow run /mnt/scc8t/zhousq/nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $genus . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '-profile docker,base -resume --outdir results -w work';
            } else {
                //singleEnds
                $cmd = '/mnt/scc8t/zhousq/nextflow run /mnt/scc8t/zhousq/nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $genus . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '--singleEnds -profile docker,base -resume --outdir results -w work';
            }

            /**
             * jobs表中添加记录
             */
            $user_id = Auth::user()->id;
            $sample_id = $request->input('sampleID');
            Jobs::create([
                'user_id' => $user_id,
                'sample_id' => $sample_id,
                'uuid' => 'default',
                'started' => '000',
                'command' => $cmd,
                'output_work' => 'default',
                'output_results' => 'default',
                'status' => 0   // 0:未开始
            ]);
            RunPipeline::dispatch($sample_id);
            return redirect('/execute/start?sampleID=' . $sample_id);
        }
        $pipelineParams = pipelineParams::find(1);
        $sample_id = $request->input('sampleID');
        if (Execparams::where('samples_id', $sample_id)->get()->count() != 0) {
            $data = Execparams::where('samples_id', $sample_id);
            $ass = $data->value('ass');    //boolean
            $cnv = $data->value('cnv');    //boolean
            $snv = $data->value('snv');    //boolean
            $bulk = $data->value('bulk');    //boolean
            $saturation = $data->value('saturation');    //boolean
            $acquired = $data->value('acquired');    //boolean
            $saveTrimmed = $data->value('saveTrimmed');    //boolean
            $saveAlignedIntermediates = $data->value('saveAlignedIntermediates');    //boolean
            $genus = $data->value('genus');     //boolean
            $genus_name = $data->value('genus_name');    //string
            $resfinder_db = $data->value('resfinder_db');     //boolean
            $nt_db = $data->value('nt_db');     //boolean
            $kraken_db = $data->value('kraken_db');     //boolean
            $eggnog = $data->value('eggnog');    //boolean
            $kofam_profile = $data->value('kofam_profile');    //boolean
            $kofam_kolist = $data->value('kofam_kolist');     //boolean
            return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'sample_id', 'pipelineParams'));
        } else {
            $ass = $cnv = $snv = $bulk = $saturation = $acquired = $saveTrimmed = $saveAlignedIntermediates = $genus = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = false;
            $genus_name = null;
            return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'sample_id', 'pipelineParams'));
        }
    }

    public function start(Request $request)
    {
        $pipelineParams = pipelineParams::find(1);
        $samples = new Samples();
        $sample_id = $request->input('sampleID');
        $data = Execparams::where('samples_id', $sample_id);
        $ass = $data->value('ass');    //boolean
        $cnv = $data->value('cnv');    //boolean
        $snv = $data->value('snv');    //boolean
        $bulk = $data->value('bulk');    //boolean
        $saturation = $data->value('saturation');    //boolean
        $acquired = $data->value('acquired');    //boolean
        $saveTrimmed = $data->value('saveTrimmed');    //boolean
        $saveAlignedIntermediates = $data->value('saveAlignedIntermediates');    //boolean
        $genus = $data->value('genus');     //boolean
        $genus_name = $data->value('genus_name');    //string
        $resfinder_db = $data->value('resfinder_db');     //boolean
        $nt_db = $data->value('nt_db');     //boolean
        $kraken_db = $data->value('kraken_db');     //boolean
        $eggnog = $data->value('eggnog');    //boolean
        $kofam_profile = $data->value('kofam_profile');    //boolean
        $kofam_kolist = $data->value('kofam_kolist');     //boolean
        return view('Pipeline.pipelineStart', compact('samples', 'ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'sample_id', 'pipelineParams'));
    }

    public function ajax(Request $request)
    {
        $run_sample_user = $request->input('run_sample_user');
        $nextflow_log_path = $run_sample_user . '/.nextflow.log';
        if (Storage::disk('local')->exists($nextflow_log_path)) {
            $data = Storage::get($nextflow_log_path);
            return response()->json(['code' => '200', 'data' => $data]);
        } else {
            return response()->json(['code' => '201', 'data' => '']);
        }
    }
}
