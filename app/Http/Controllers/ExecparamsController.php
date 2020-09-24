<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Execparams;
use App\pipelineParams;
use App\Status;
use App\Samples;
use App\Jobs\RunPipeline;

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
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
            RunPipeline::dispatch($sample_id);

            // Status表
            $start = time();
            $user_id = Auth::user()->id;
            if (Status::where('sample_id', $sample_id)->get()->count() == 0) {
                Status::create([
                    'user_id' => $user_id,
                    'sample_id' => $sample_id,
                    'started' => $start,
                    'status' => false
                ]);
            } else {
                $status_id = Status::where('sample_id', $sample_id)->value('id');
                $status = Status::find($status_id);
                $status->started = $start;
                $status->save();
            }
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
        // $run_sample_user = $request->input('run_sample_user');
        // $nextflow_log_path = $run_sample_user . '/.nextflow.log';
        // if (Storage::disk('local')->exists($nextflow_log_path)) {
        //     $data = Storage::get($nextflow_log_path);
        //     return response()->json(['success' => true, 'data' => $data]);
        // } else {
        //     return response()->json(['success' => true, 'data' => 'can not read .nextflow.log!']);
        // }

        $data = Storage::get('.nextflow.log');
        return response()->json(['success' => true, 'data' => $data]);
    }
}
