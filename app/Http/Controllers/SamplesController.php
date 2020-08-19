<?php

namespace App\Http\Controllers;

use App\Samples;
use App\Projects;
use App\Labs;
use App\Applications;
use App\Species;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            // 数据库中没有samples时显示
            $selectSamples = null;
            return view('Sample.samples', compact('selectSamples', 'projectID', 'project'));
        }
    }

    public function create(Request $request)
    {
        $applications = Applications::all();
        $all_species = Species::all();
        if ($request->isMethod('POST')) {
            // samples create validate
            $this->validate($request, [
                'new_sample_label' => 'required|max:250',
                'select_application' => 'required',
                'select_species' => 'nullable',
                'isPairends' => 'required',
                'new_fileOne' => ['required', 'regex:{((\w)+\/)*(\w)+\.fasta$}'],
                'new_fileTwo' => ['nullable', 'regex:{((\w)+\/)*(\w)+\.fasta$}']
            ]);
            $projectID = $request->input('projectID');
            $new_sample_label = $request->input('new_sample_label');
            $select_application = $request->input('select_application');
            $select_species = $request->input('select_species');
            switch ($request->input('isPairends')) {
                case 'singleEnds':
                    $isPairends = 0;
                    break;
                case 'pairEnds':
                    $isPairends = 1;
                    break;
            }
            $fileOne = $request->input('new_fileOne');
            $fileTwo = $request->input('new_fileTwo');
            if ($fileTwo == null) {
                // 验证file1、file2是否存在，如不存在，返回错误信息
                $file1_exist = Storage::disk('local')->exists($fileOne);
                if ($file1_exist) {
                    Samples::create([
                        'sampleLabel' => $new_sample_label,
                        'applications_id' => $select_application,
                        'projects_id' => $projectID,
                        'species_id' => $select_species,
                        'pairends' => $isPairends,
                        'filename1' => $fileOne,
                        'filename2' => $fileTwo
                    ]);
                    return redirect('/samples?projectID=' . $projectID);
                } else {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error]);
                }
            } else {
                $file1_exist = Storage::disk('local')->exists($fileOne);
                $file2_exist = Storage::disk('local')->exists($fileTwo);
                if (!$file1_exist && $file2_exist) {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error]);
                } elseif ($file1_exist && !$file2_exist) {
                    $file_error = 'file2 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error]);
                } elseif (!$file1_exist && !$file2_exist) {
                    $file_error = 'file1 and file2 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error]);
                } else {
                    Samples::create([
                        'sampleLabel' => $new_sample_label,
                        'applications_id' => $select_application,
                        'projects_id' => $projectID,
                        'species_id' => $select_species,
                        'pairends' => $isPairends,
                        'filename1' => $fileOne,
                        'filename2' => $fileTwo
                    ]);
                    return redirect('/samples?projectID=' . $projectID);
                }
            }
        }
        return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species]);
    }

    public function delete(Request $request)
    {
        $samp_id = $request->input('sampleID');
        $project_id = $request->input('projectID');
        $sample = Samples::find($samp_id);
        $sample->delete();
        return redirect('/samples?projectID=' . $project_id);
    }

    public function update(Request $request)
    {
        $sample_id = $request->input('sampleID');
        $sample = Samples::find($sample_id);
        $app = Applications::find($sample['applications_id']);
        $applications = Applications::all();
        $all_species = Species::all();
        if ($request->isMethod('POST')) {
            // sample update validate
            $this->validate($request, [
                'sample_label' => 'required|max:50',
                'select_application' => 'required',
                'select_species' => 'nullable',
                'isPairends' => 'required',
                'fileOne' => ['required', 'regex:{((\w)+\/)*(\w)+\.fasta$}'],
                'fileTwo' => ['nullable', 'regex:{((\w)+\/)*(\w)+\.fasta$}']
            ]);
            $projectID = $request->input('projectID');
            $sample_label = $request->input('sample_label');
            $select_application = $request->input('select_application');
            $select_species = $request->input('select_species');
            switch ($request->input('isPairends')) {
                case 'singleEnds':
                    $isPairends = 0;
                    break;
                case 'pairEnds':
                    $isPairends = 1;
                    break;
            }
            $fileOne = $request->input('fileOne');
            $fileTwo = $request->input('fileTwo');
            if ($fileTwo == null) {
                // 验证file1、file2是否存在，如不存在，返回错误信息
                $file1_exist = Storage::disk('local')->exists($fileOne);
                if ($file1_exist) {
                    $sample['sampleLabel'] = $sample_label;
                    $sample['applications_id'] = $select_application;
                    $sample['species_id'] = $select_species;
                    $sample['pairends'] = $isPairends;
                    $sample['filename1'] = $fileOne;
                    $sample['filename2'] = $fileTwo;
                    $sample->save();
                    return redirect('/samples?projectID=' . $projectID);
                } else {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app]);
                }
            } else {
                $file1_exist = Storage::disk('local')->exists($fileOne);
                $file2_exist = Storage::disk('local')->exists($fileTwo);
                if (!$file1_exist && $file2_exist) {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app]);
                } elseif ($file1_exist && !$file2_exist) {
                    $file_error = 'file2 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app]);
                } elseif (!$file1_exist && !$file2_exist) {
                    $file_error = 'file1 and file2 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app]);
                } else {
                    $sample['sampleLabel'] = $sample_label;
                    $sample['applications_id'] = $select_application;
                    $sample['species_id'] = $select_species;
                    $sample['pairends'] = $isPairends;
                    $sample['filename1'] = $fileOne;
                    $sample['filename2'] = $fileTwo;
                    $sample->save();
                    return redirect('/samples?projectID=' . $projectID);
                }
            }
        }
        return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'sample' => $sample, 'app' => $app]);
    }
}
