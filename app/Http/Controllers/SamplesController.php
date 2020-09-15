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
use Illuminate\Support\Str;
use App\pipelineParams;
use App\Execparams;

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
        $sample = new Samples();
        try {
            // 登录用户显示
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $current_lab_id], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $isAdmin = $user->email == 'admin@123.com';
                return view('Samples.samples', compact('selectSamples', 'isPI', 'isAdmin', 'projectID', 'project', 'sample'));
            } else {
                // 未登录用户显示
                $isPI  = false;
                $isAdmin = false;
                return view('Samples.samples', compact('selectSamples', 'isPI', 'isAdmin', 'projectID', 'project', 'sample'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            // 数据库中没有samples时显示
            $selectSamples = null;
            return view('Sample.samples', compact('selectSamples', 'projectID', 'project', 'applications'));
        }
    }

    public function create(Request $request)
    {
        $applications = Applications::all();
        $all_species = Species::all();
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            // samples create validate
            $this->validate($request, [
                'new_sample_label' => 'required|max:250',
                'select_application' => 'required',
                'select_species' => 'nullable',
                'isPairends' => 'required',
                'new_fileOne' => ['required', 'regex:{(\.R1)?(_1)?(_R1)?(_trimmed)?(_combined)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$}'],
                'new_fileTwo' => ['nullable', 'regex:{(\.R2)?(_2)?(_R2)?(_trimmed)?(_combined)?(\.2_val_2)?(_R2_val_2)?(\.fq)?(\.fastq)?(\.gz)?$}']
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
                // 验证file1是否存在
                if (strpos($fileOne, $base_path) == 0) {
                    //  判断输入是否是绝对路径
                    $file1_path = str_replace($base_path, '', $fileOne);
                    $file1_exist = Storage::disk('local')->exists($file1_path);
                } else {
                    $file1_exist = Storage::disk('local')->exists($fileOne);
                }

                if ($file1_exist) {
                    //  统一保存为相对路径
                    $fileOne = $file1_path ? $file1_path : $fileOne;

                    //  判断是否存在反斜杠\
                    $fileOne = strpos($fileOne, '\\') !== false ? str_replace('\\', '/', $fileOne) : $fileOne;
                    Samples::create([
                        'sampleLabel' => $new_sample_label,
                        'applications_id' => $select_application,
                        'projects_id' => $projectID,
                        'species_id' => $select_species,
                        'pairends' => $isPairends,
                        'filename1' => $fileOne,
                        'filename2' => $fileTwo
                    ]);
                    if ($request->input('from')) {
                        return redirect('/workspace/samples?projectID=' . $projectID);
                    } else {
                        return redirect('/samples?projectID=' . $projectID);
                    }
                } else {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'base_path' => $base_path]);
                }
            } else {
                // 验证file1,file2是否存在
                if (strpos($fileOne, $base_path) == 0) {
                    $file1_path = str_replace($base_path, '', $fileOne);
                    $file1_exist = Storage::disk('local')->exists($file1_path);
                } else {
                    $file1_exist = Storage::disk('local')->exists($fileOne);
                }
                if (strpos($fileTwo, $base_path) == 0) {
                    $file2_path = str_replace($base_path, '', $fileTwo);
                    $file2_exist = Storage::disk('local')->exists($file2_path);
                } else {
                    $file2_exist = Storage::disk('local')->exists($fileTwo);
                }
                // 判断返回错误信息
                if (!$file1_exist && $file2_exist) {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'base_path' => $base_path]);
                } elseif ($file1_exist && !$file2_exist) {
                    $file_error = 'file2 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'base_path' => $base_path]);
                } elseif (!$file1_exist && !$file2_exist) {
                    $file_error = 'file1 and file2 doesn\'t exist';
                    return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'base_path' => $base_path]);
                } else {
                    //  统一保存为相对路径
                    $fileOne = $file1_path ? $file1_path : $fileOne;
                    $fileTwo = $file2_path ? $file2_path : $fileTwo;

                    //  判断是否存在反斜杠\
                    $fileOne = strpos($fileOne, '\\') !== false ? str_replace('\\', '/', $fileOne) : $fileOne;
                    $fileTwo = strpos($fileTwo, '\\') !== false ? str_replace('\\', '/', $fileTwo) : $fileTwo;

                    Samples::create([
                        'sampleLabel' => $new_sample_label,
                        'applications_id' => $select_application,
                        'projects_id' => $projectID,
                        'species_id' => $select_species,
                        'pairends' => $isPairends,
                        'filename1' => $fileOne,
                        'filename2' => $fileTwo
                    ]);
                    if ($request->input('from')) {
                        return redirect('/workspace/samples?projectID=' . $projectID);
                    } else {
                        return redirect('/samples?projectID=' . $projectID);
                    }
                }
            }
        }
        return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'base_path' => $base_path]);
    }

    public function delete(Request $request)
    {
        $samp_id = $request->input('sampleID');
        $project_id = $request->input('projectID');
        $sample = Samples::find($samp_id);
        $sample->delete();
        if ($request->input('from')) {
            return redirect('/workspace/samples?projectID=' . $project_id);
        } else {
            return redirect('/samples?projectID=' . $project_id);
        }
    }

    public function update(Request $request)
    {
        $sample_id = $request->input('sampleID');
        $sample = Samples::find($sample_id);
        $app = Applications::find($sample['applications_id']);
        $applications = Applications::all();
        $all_species = Species::all();
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            // sample update validate
            $this->validate($request, [
                'sample_label' => 'required|max:50',
                'select_application' => 'required',
                'select_species' => 'nullable',
                'isPairends' => 'required',
                'fileOne' => ['required', 'regex:{(\.R1)?(_1)?(_R1)?(_trimmed)?(_combined)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$}'],
                'fileTwo' => ['nullable', 'regex:{(\.R2)?(_2)?(_R2)?(_trimmed)?(_combined)?(\.2_val_2)?(_R2_val_2)?(\.fq)?(\.fastq)?(\.gz)?$}']
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
                // 验证file1是否存在
                if (strpos($fileOne, $base_path) == 0) {
                    //  判断输入是否是绝对路径
                    $file1_path = str_replace($base_path, '', $fileOne);
                    $file1_exist = Storage::disk('local')->exists($file1_path);
                } else {
                    $file1_exist = Storage::disk('local')->exists($fileOne);
                }
                if ($file1_exist) {
                    //  判断是否为相对路径
                    $fileOne = $file1_path ? $file1_path : $fileOne;

                    //  判断是否存在反斜杠\
                    $fileOne = strpos($fileOne, '\\') !== false ? str_replace('\\', '/', $fileOne) : $fileOne;
                    $sample['sampleLabel'] = $sample_label;
                    $sample['applications_id'] = $select_application;
                    $sample['species_id'] = $select_species;
                    $sample['pairends'] = $isPairends;
                    $sample['filename1'] = $fileOne;
                    $sample['filename2'] = $fileTwo;
                    $sample->save();
                    if ($request->input('from')) {
                        // 从workspace - myProject中访问
                        return redirect('/workspace/samples?projectID=' . $projectID);
                    } else {
                        // 从home - sample访问
                        return redirect('/samples?projectID=' . $projectID);
                    }
                } else {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path]);
                }
            } else {
                // 验证file1,file2是否存在
                if (strpos($fileOne, $base_path) == 0) {
                    $file1_path = str_replace($base_path, '', $fileOne);
                    $file1_exist = Storage::disk('local')->exists($file1_path);
                } else {
                    $file1_exist = Storage::disk('local')->exists($fileOne);
                }
                if (strpos($fileTwo, $base_path) == 0) {
                    $file2_path = str_replace($base_path, '', $fileTwo);
                    $file2_exist = Storage::disk('local')->exists($file2_path);
                } else {
                    $file2_exist = Storage::disk('local')->exists($fileTwo);
                }
                // 判断返回错误信息
                if (!$file1_exist && $file2_exist) {
                    $file_error = 'file1 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path]);
                } elseif ($file1_exist && !$file2_exist) {
                    $file_error = 'file2 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path]);
                } elseif (!$file1_exist && !$file2_exist) {
                    $file_error = 'file1 and file2 doesn\'t exist';
                    return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'file_error' => $file_error, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path]);
                } else {
                    //  判断输入是否是相对路径
                    $fileOne = $file1_path ? $file1_path : $fileOne;
                    $fileTwo = $file2_path ? $file2_path : $fileTwo;

                    //  判断是否存在反斜杠\
                    $fileOne = strpos($fileOne, '\\') !== false ? str_replace('\\', '/', $fileOne) : $fileOne;
                    $fileTwo = strpos($fileTwo, '\\') !== false ? str_replace('\\', '/', $fileTwo) : $fileTwo;
                    $sample['sampleLabel'] = $sample_label;
                    $sample['applications_id'] = $select_application;
                    $sample['species_id'] = $select_species;
                    $sample['pairends'] = $isPairends;
                    $sample['filename1'] = $fileOne;
                    $sample['filename2'] = $fileTwo;
                    $sample->save();
                    if ($request->input('from')) {
                        return redirect('/workspace/samples?projectID=' . $projectID);
                    } else {
                        return redirect('/samples?projectID=' . $projectID);
                    }
                }
            }
        }
        return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path]);
    }
}
