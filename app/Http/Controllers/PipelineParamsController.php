<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pipelineParams;

class PipelineParamsController extends Controller
{
    //
    public function index()
    {
        if (pipelineParams::where('id', 1)->exists()) {
            $pipelineParams = pipelineParams::find(1);
            return view('Workspace.pipelineParams', ['pipelineParams' => $pipelineParams]);
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
                'kofam_kolist_path' => 'required|max:200'
            ]);
            $resfinder_db_path = $request->input('resfinder_db_path');
            $nt_db_path = $request->input('nt_db_path');
            $kraken_db_path = $request->input('kraken_db_path');
            $eggnog_db_path = $request->input('eggnog_db_path');
            $kofam_profile_path = $request->input('kofam_profile_path');
            $kofam_kolist_path = $request->input('kofam_kolist_path');
            if (pipelineParams::where('id', 1)->exists()) {
                $pipelineParams = pipelineParams::find(1);
                $pipelineParams->resfinder_db_path = $resfinder_db_path;
                $pipelineParams->nt_db_path = $nt_db_path;
                $pipelineParams->kraken_db_path = $kraken_db_path;
                $pipelineParams->eggnog_db_path = $eggnog_db_path;
                $pipelineParams->kofam_profile_path = $kofam_profile_path;
                $pipelineParams->kofam_kolist_path = $kofam_kolist_path;
                $pipelineParams->save();
            } else {
                pipelineParams::create([
                    'resfinder_db_path' => $resfinder_db_path,
                    'nt_db_path' => $nt_db_path,
                    'kraken_db_path' => $kraken_db_path,
                    'eggnog_db_path' => $eggnog_db_path,
                    'kofam_profile_path' => $kofam_profile_path,
                    'kofam_kolist_path' => $kofam_kolist_path
                ]);
            }
            return redirect('/workspace/pipelineParams');
        }
        if (pipelineParams::where('id', 1)->exists()) {
            $pipelineParams = pipelineParams::find(1);
            return view('Workspace.pipelineParamsEdit', ['pipelineParams' => $pipelineParams]);
        }
        return view('Workspace.pipelineParamsEdit');
    }
}