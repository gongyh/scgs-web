<?php

namespace App\Http\Controllers;

use App\Samples;
use App\Projects;
use App\Labs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SamplesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $projectID = $request->input('projectID');
        $project = Projects::find($projectID);
        $current_lab_id = Projects::where('id', $projectID)->value('labs_id');
        $selectSamples = Samples::where('projects_id', $projectID)->paginate(8);
        $selectSamples->withPath('/samples?projectID=' . $projectID);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $current_lab_id], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $isAdmin = $user->email == 'admin@123.com';
                return view('Samples.samples', compact('selectSamples', 'isPI', 'isAdmin', 'projectID', 'project'));
            } else {
                $isPI  = false;
                $isAdmin = false;
                return view('Samples.samples', compact('selectSamples', 'isPI', 'isAdmin', 'projectID', 'project'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectSamples = null;
            return view('Sample.samples', compact('selectSamples', 'projectID', 'project'));
        }
    }

    public function update(Request $request)
    {
        $sampleID = $request->input('sampleID');
        $projectID = $request->input('projectID');
        $sample = Samples::find($sampleID);
        if ($request->isMethod('POST')) {
            $new_sample_label = $request->input('new_sampleLabel');
            $new_pairEnds = $request->input('new_pairEnds');
            try {
                $sample['sampleLabel'] = $new_sample_label;
                $sample['pairends'] = $new_pairEnds;
                $sample->save();
                return redirect('/samples?projectID=' . $projectID);
            } catch (\Illuminate\Database\QueryException $ex) {
                return 'Sorry!You have not input the sample label!';
            }
        }
        return view('Samples.samp_update', ['sample' => $sample]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $projectID = $request->input('projectID');
            $new_sample_label = $request->input('new_sample_label');
            $new_pair_ends = $request->input('new_pair_ends');
            try {
                Samples::create([
                    'sampleLabel' => $new_sample_label,
                    'pairends' => $new_pair_ends
                ]);
                return redirect('/samples?projectID=' . $projectID);
            } catch (\Illuminate\Database\QueryException $ex) {
                return 'Sorry!You have not input the sample label!';
            }
        }
        return view('Samples.samp_create');
    }

    public function delete(Request $request)
    {
        $samp_id = $request->input('sampleID');
        $project_id = $request->input('projectID');
        $sample = Samples::find($samp_id);
        $sample->delete();
        return redirect('/samples?projectID=' . $project_id);
    }
}
