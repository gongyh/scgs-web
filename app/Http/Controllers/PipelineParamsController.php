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
                'kraken_db_path' => 'required|max:200',
                'eggnog_db_path' => 'required|max:200',
                'kofam_profile_path' => 'required|max:200',
                'kofam_kolist_path' => 'required|max:200',
                'eukcc_db_path' => 'required|max:200',
                'nextflow_path' => 'required|max:200',
                'nf_core_scgs_path' => 'required|max:200',
                'nextflow_profile' => 'required'
            ]);
            $resfinder_db_path = $request->input('resfinder_db_path');
            $nt_db_path = $request->input('nt_db_path');
            $kraken_db_path = $request->input('kraken_db_path');
            $eggnog_db_path = $request->input('eggnog_db_path');
            $kofam_profile_path = $request->input('kofam_profile_path');
            $kofam_kolist_path = $request->input('kofam_kolist_path');
            $eukcc_db_path = $request->input('eukcc_db_path');
            $nextflow_path = $request->input('nextflow_path');
            $nf_core_scgs_path = $request->input('nf_core_scgs_path');
            $nextflow_profile = $request->input('nextflow_profile');

            /**Pipeline params files validated by administrator
             *
            $resfinder_db_exist = Storage::disk('local')->exists($resfinder_db_path);
            $nt_db_exist = Storage::disk('local')->exists($nt_db_path);
            $eggnog_db_exist = Storage::disk('local')->exists($eggnog_db_path);
            $kraken_db_exist = Storage::disk('local')->exists($kraken_db_path);
            $kofam_profile_exist = Storage::disk('local')->exists($kofam_profile_path);
            $kofam_kolist_exist = Storage::disk('local')->exists($resfinder_db_path);

            $resfinder_error = $resfinder_db_exist ? null : 'resfinder db path doesn\'t exist';
            $nt_error = $nt_db_exist ? null : 'nt db path doesn\'t exist';
            $kraken_error = $kraken_db_exist ? null : 'kraken db path doesn\'t exist';
            $eggnog_error = $eggnog_db_exist ? null : 'eggnog db path doesn\'t exist';
            $kofam_profile_error = $kofam_profile_exist ? null : 'kofam profile path doesn\'t exist';
            $kofam_kolist_error = $kofam_kolist_exist ? null : 'kofam kolist path doesn\'t exist';
            $db_path_errors = array($resfinder_error, $nt_error, $kraken_error, $eggnog_error, $kofam_profile_error, $kofam_kolist_error);
            $db_path_errors = array_filter($db_path_errors);

            if (count($db_path_errors) > 0) {
                return view('Workspace.pipelineParamsEdit', ['db_path_errors' => $db_path_errors]);
            }
             */

            if (PipelineParams::where('id', 1)->exists()) {
                $PipelineParams = PipelineParams::find(1);
                $PipelineParams->resfinder_db_path = $resfinder_db_path;
                $PipelineParams->nt_db_path = $nt_db_path;
                $PipelineParams->kraken_db_path = $kraken_db_path;
                $PipelineParams->eggnog_db_path = $eggnog_db_path;
                $PipelineParams->kofam_profile_path = $kofam_profile_path;
                $PipelineParams->kofam_kolist_path = $kofam_kolist_path;
                $PipelineParams->eukcc_db_path = $eukcc_db_path;
                $PipelineParams->nextflow_path = $nextflow_path;
                $PipelineParams->nf_core_scgs_path = $nf_core_scgs_path;
                $PipelineParams->nextflow_profile = $nextflow_profile;
                $PipelineParams->save();
            } else {
                PipelineParams::create([
                    'resfinder_db_path' => $resfinder_db_path,
                    'nt_db_path' => $nt_db_path,
                    'kraken_db_path' => $kraken_db_path,
                    'eggnog_db_path' => $eggnog_db_path,
                    'kofam_profile_path' => $kofam_profile_path,
                    'kofam_kolist_path' => $kofam_kolist_path,
                    'eukcc_db_path' => $eukcc_db_path,
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
