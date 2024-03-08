<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PipelineParams;

class PipelineParamsController extends Controller
{
    //
    public function index()
    {
        if (PipelineParams::where('id', 1)->exists()) {
            $PipelineParams = PipelineParams::find(1);
            return view('Workspace.pipelineParams', ['PipelineParams' => $PipelineParams]);
        }
        return view('Workspace.pipelineParams');
    }

    public function update(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'resfinder_db_path' => 'required|max:200',
                'nt_db_path' => 'required|max:200',
                'blob_path' => 'required|max:200',
                'kraken_db_path' => 'required|max:200',
                'kraken2_db_path' => 'required|max:200',
                'eggnog_db_path' => 'required|max:200',
                'kofam_profile_path' => 'required|max:200',
                'kofam_kolist_path' => 'required|max:200',
                'eukcc_db_path' => 'required|max:200',
                'checkm2_db_path' => 'required|max:200',
                'gtdb_path' => 'required|max:200',
                'nextflow_path' => 'required|max:200',
                'nf_core_scgs_path' => 'required|max:200',
                'nextflow_profile' => 'required'
            ]);
            $resfinder_db_path = $request->input('resfinder_db_path');
            $nt_db_path = $request->input('nt_db_path');
            $blob_path = $request->input('blob_path');
            $kraken_db_path = $request->input('kraken_db_path');
            $kraken2_db_path = $request->input('kraken2_db_path');
            $eggnog_db_path = $request->input('eggnog_db_path');
            $kofam_profile_path = $request->input('kofam_profile_path');
            $kofam_kolist_path = $request->input('kofam_kolist_path');
            $checkm2_db_path = $request->input('checkm2_db_path');
            $eukcc_db_path = $request->input('eukcc_db_path');
            $gtdb_path = $request->input('gtdb_path');
            $nextflow_path = $request->input('nextflow_path');
            $nf_core_scgs_path = $request->input('nf_core_scgs_path');
            $nextflow_profile = $request->input('nextflow_profile');

            if (PipelineParams::where('id', 1)->exists()) {
                $PipelineParams = PipelineParams::find(1);
                $PipelineParams->resfinder_db_path = $resfinder_db_path;
                $PipelineParams->nt_db_path = $nt_db_path;
                $PipelineParams->blob_path = $blob_path;
                $PipelineParams->kraken_db_path = $kraken_db_path;
                $PipelineParams->kraken2_db_path = $kraken2_db_path;
                $PipelineParams->eggnog_db_path = $eggnog_db_path;
                $PipelineParams->kofam_profile_path = $kofam_profile_path;
                $PipelineParams->kofam_kolist_path = $kofam_kolist_path;
                $PipelineParams->checkm2_db_path = $checkm2_db_path;
                $PipelineParams->eukcc_db_path = $eukcc_db_path;
                $PipelineParams->gtdb_path = $gtdb_path;
                $PipelineParams->nextflow_path = $nextflow_path;
                $PipelineParams->nf_core_scgs_path = $nf_core_scgs_path;
                $PipelineParams->nextflow_profile = $nextflow_profile;
                $PipelineParams->save();
            } else {
                PipelineParams::create([
                    'resfinder_db_path' => $resfinder_db_path,
                    'nt_db_path' => $nt_db_path,
                    'blob_path' => $blob_path,
                    'kraken_db_path' => $kraken_db_path,
                    'kraken2_db_path' => $kraken2_db_path,
                    'eggnog_db_path' => $eggnog_db_path,
                    'kofam_profile_path' => $kofam_profile_path,
                    'kofam_kolist_path' => $kofam_kolist_path,
                    'checkm2_db_path' => $checkm2_db_path,
                    'eukcc_db_path' => $eukcc_db_path,
                    'gtdb_path' => $gtdb_path,
                    'nextflow_path' => $nextflow_path,
                    'nf_core_scgs_path' => $nf_core_scgs_path,
                    'nextflow_profile' => $nextflow_profile
                ]);
            }
            return redirect('/workspace/pipelineParams');
        }
        if (PipelineParams::where('id', 1)->exists()) {
            $PipelineParams = PipelineParams::find(1);
            return view('Workspace.pipelineParamsEdit', ['PipelineParams' => $PipelineParams]);
        }
        return view('Workspace.pipelineParamsEdit');
    }
}
