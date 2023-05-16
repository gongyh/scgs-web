<?php

namespace App\Http\Controllers;

use App\Samples;
use App\Projects;
use App\Labs;
use App\User;
use App\Jobs;
use App\Applications;
use App\Species;
use App\Jobs\MvSamples;
use App\Imports\SamplesImport;
use Maatwebsite\Excel\Facades\Excel;
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
        ## Project status
        $projectID = $request->input('projectID');
        $current_page = $request->input('page');
        $pageSize = 8;
        $project = Projects::find($projectID);
        $current_lab_id = Projects::where('id', $projectID)->value('labs_id');
        $selectSamples = Samples::where('projects_id', $projectID)->paginate(8);
        $canRun = $selectSamples->count() > 0 ? true : false;
        if (Jobs::where('project_id', $projectID)->count() == 0) {
            $status = 'not analyzed';
        } elseif ((Jobs::where('project_id', $projectID)->count() > 0 && Jobs::where('project_id', $projectID)->orderBy('id', 'desc')->value('status') == 0)) {
            $status = 'queueing';
        } elseif (Jobs::where('project_id', $projectID)->count() > 0 && Jobs::where('project_id', $projectID)->orderBy('id', 'desc')->value('status') == 1) {
            $status = 'running';
        } elseif (Jobs::where('project_id', $projectID)->count() > 0 && Jobs::where('project_id', $projectID)->orderBy('id', 'desc')->value('status') == 2) {
            $status = 'failed';
        } elseif (Jobs::where('project_id', $projectID)->count() > 0 && Jobs::where('project_id', $projectID)->orderBy('id', 'desc')->value('status') == 3) {
            $status = 'success';
        } else {
            $status = '';
        }
        //to judge if can be released
        $now = date("Y-m-d");
        $release_date = Projects::where('id', $projectID)->value('release_date');
        strtotime($now) - strtotime($release_date) > 0 ? $is_release = true : $is_release = false;
        $selectSamples->withPath('/samples?projectID=' . $projectID);
        $sample = new Samples();
        try {
            // login users
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $current_lab_id], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $user->email == env('ADMIN_EMAIL') ? $isAdmin = true : $isAdmin = false;
                return view('Samples.samples', compact('selectSamples', 'current_page', 'pageSize', 'isPI', 'isAdmin', 'projectID', 'project', 'sample', 'canRun', 'status', 'is_release'));
            } else {
                // not login users
                $isPI  = false;
                $isAdmin = false;
                return view('Samples.samples', compact('selectSamples', 'current_page', 'pageSize', 'isPI', 'isAdmin', 'projectID', 'project', 'sample', 'canRun', 'status', 'is_release'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            // No samples
            $selectSamples = null;
            return view('Samples.samples', compact('selectSamples', 'current_page', 'pageSize', 'projectID', 'project', 'applications', 'canRun', 'status', 'is_release'));
        }
    }

    public function create(Request $request)
    {
        $projectID = $request->input('projectID');
        $lab_id = Projects::where('id', $projectID)->value('labs_id');
        $user = Labs::where('id', $lab_id)->value('principleInvestigator');
        $user = User::where('name', $user)->value('name');
        $applications = Applications::all();
        $all_species = Species::all();
        $sample_files =  Storage::disk('local')->exists('meta-data/' . $user) ? Storage::files('meta-data/' . $user) : array();
        $file1 = array();
        $file2 = array();
        foreach ($sample_files as $sample_file) {
            $file_prefix = 'meta-data/' . $user . '/';
            $sample_file = str_replace($file_prefix, '', $sample_file);
            if (strpos($sample_file, 'R1') || strpos($sample_file, '_1') || strpos($sample_file, '.1') || strpos($sample_file, '.1_val_1') || strpos($sample_file, '_R1_val_1')) {
                array_push($file1, $sample_file);
            } else {
                array_push($file2, $sample_file);
            }
        }
        $library_strategies = array('WGA', 'WGS', 'WXS', 'RNA-Seq', 'miRNA-Seq', 'WCS', 'CLONE', 'POOLCLONE', 'AMPLICON', 'FINISHING', 'CLONEEND', 'CHIP-Seq', 'MNase-Seq', 'DNase-Hypersensitivity', 'Bisulfite-Seq', 'Tn-Seq', 'EST', 'FL-cDNA', 'CTS', 'MRE-Seq', 'MeDIP-Seq', 'MBD-Seq', 'Synthetic-Long-Read', 'ATAC-Seq', 'ChIA-PET', 'FAIRE-seq', 'Hi-C', 'ncRNA-Seq', 'RAD-Seq', 'RIP-Seq', 'SELEX', 'ssRNA-seq', 'Targeted-Capture', 'Tethered Chromation Conformation Capture', 'OTHER');
        $library_sources = array('GENOMIC', 'TRANSCRIPTOMIC', 'METAGENOMIC', 'METATRANSCRIPTOMIC', 'SYNTHETIC', 'VIRAL RNA', 'GENOMIC SINGLE CELL', 'TRANSCRIPTOMIC SINGLE CELL', 'OTHER');
        $library_selections = array('RANDOM', 'PCR', 'RANDOM PCR', 'HMPR', 'MF', 'CF-S', 'CF-M', 'CF-H', 'CF-T', 'MDA', 'MSLL', 'cDNA', 'CHIP', 'MNase', 'DNAse', 'Hybrid Selection', 'Reduced Representation', 'Restriction Digest', '5-methylcytidine antibody', 'MBD2 protein methyl-CpG binding domain', 'CAGE', 'RACE', 'size fractionation', 'Padlock probes capture method', 'other', 'unspecified', 'cDNA_oligo_dT', 'cDNA_randomPriming', 'Oligo-dT', 'PolyA', 'repeat fractionation');
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'new_sample_label' => 'required|max:250',
                'new_library_id' => 'required|max:150',
                'library_strategy' => 'required',
                'library_source' => 'required',
                'library_selection' => 'required',
                'platform' => 'required',
                'instrument_model' => 'required',
                'design_description' => 'required|max:500',
                'select_application' => 'required',
                'select_species' => 'nullable'
            ]);
            $projectID = $request->input('projectID');
            $new_sample_label = $request->input('new_sample_label');
            $new_library_id = $request->input('new_library_id');
            $library_strategy = $request->input('library_strategy');
            $library_source = $request->input('library_source');
            $library_selection = $request->input('library_selection');
            $platform = $request->input('platform');
            $instrument_model = $request->input('instrument_model');
            $design_description = $request->input('design_description');
            $select_application = $request->input('select_application');
            $select_species = $request->input('select_species');
            if ($request->has('isPairends')) {
                // samples create validate
                $this->validate($request, [
                    'isPairends' => 'required',
                    'new_fileOne' => ['required', 'regex:{(\.R1)?(_1)?(_R1)?(_trimmed)?(_combined)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$}'],
                    'new_fileTwo' => ['nullable', 'regex:{(\.R2)?(_2)?(_R2)?(_trimmed)?(_combined)?(\.2_val_2)?(_R2_val_2)?(\.fq)?(\.fastq)?(\.gz)?$}']
                ]);
                switch ($request->input('isPairends')) {
                    case 'Single':
                        $isPairends = 0;
                        break;
                    case 'Paired-end':
                        $isPairends = 1;
                        break;
                }
                $fileOne = $request->input('new_fileOne');
                $fileTwo = $request->input('new_fileTwo');
                if (strcmp($request->input('isPairends'), 'Single') == 0) {
                    $file1_exist = Storage::disk('local')->exists('meta-data/' . $user . '/' . $fileOne);
                    if ($file1_exist) {
                        Samples::create([
                            'sampleLabel' => $new_sample_label,
                            'library_id' => $new_library_id,
                            'library_strategy' => $library_strategy,
                            'library_source' => $library_source,
                            'library_selection' => $library_selection,
                            'platform' => $platform,
                            'design_description' => $design_description,
                            'instrument_model' => $instrument_model,
                            'filetype' => 'fastq',
                            'applications_id' => $select_application,
                            'projects_id' => $projectID,
                            'species_id' => $select_species,
                            'pairends' => $isPairends,
                            'filename1' => $fileOne,
                            'filename2' => null,
                            'isPrepared' => 0,
                            'status' => 0,
                        ]);
                        MvSamples::dispatch($projectID, $fileOne, $fileTwo)->onQueue('MvSamples');
                        if ($request->input('from')) {
                            return redirect('/workspace/samples?projectID=' . $projectID);
                        } else {
                            return redirect('/samples?projectID=' . $projectID);
                        }
                    } else {
                        $file_error = 'file1 doesn\'t exist';
                        return back()->withErrors($file_error);
                    }
                } else {
                    $file1_exist = Storage::disk('local')->exists('meta-data/' . $user . '/' . $fileOne);
                    $file2_exist = Storage::disk('local')->exists('meta-data/' . $user . '/' . $fileTwo);
                    if (!$file1_exist && $file2_exist) {
                        $file_error = 'file1 doesn\'t exist';
                        return back()->withErrors($file_error);
                    } elseif ($file1_exist && !$file2_exist) {
                        $file_error = 'file2 doesn\'t exist';
                        return back()->withErrors($file_error);
                    } elseif (!$file1_exist && !$file2_exist) {
                        $file_error = 'file1 and file2 doesn\'t exist';
                        return back()->withErrors($file_error);
                    } else {
                        Samples::create([
                            'sampleLabel' => $new_sample_label,
                            'library_id' => $new_library_id,
                            'library_strategy' => $library_strategy,
                            'library_source' => $library_source,
                            'library_selection' => $library_selection,
                            'platform' => $platform,
                            'design_description' => $design_description,
                            'instrument_model' => $instrument_model,
                            'filetype' => 'fastq',
                            'applications_id' => $select_application,
                            'projects_id' => $projectID,
                            'species_id' => $select_species,
                            'pairends' => $isPairends,
                            'filename1' => $fileOne,
                            'filename2' => $fileTwo,
                            'isPrepared' => 0,
                            'status' => 0
                        ]);
                        MvSamples::dispatch($projectID, $fileOne, $fileTwo)->onQueue('MvSamples');
                        if ($request->input('from')) {
                            return redirect('/workspace/samples?projectID=' . $projectID);
                        } else {
                            return redirect('/samples?projectID=' . $projectID);
                        }
                    }
                }
            }
        }
        return view('Samples.samp_create', ['applications' => $applications, 'all_species' => $all_species, 'base_path' => $base_path, 'library_strategies' => $library_strategies, 'library_sources' => $library_sources, 'library_selections' => $library_selections, 'file1' => $file1, 'file2' => $file2]);
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
        $projectID = Samples::where('id', $sample_id)->value('projects_id');
        $lab_id = Projects::where('id', $projectID)->value('labs_id');
        $user = Labs::where('id', $lab_id)->value('principleInvestigator');
        $sample = Samples::find($sample_id);
        $app = Applications::find($sample['applications_id']);
        $applications = Applications::all();
        $all_species = Species::all();
        $sample_files =  Storage::disk('local')->exists('meta-data/' . $user) ? Storage::files('meta-data/' . $user) : array();
        $files = array();
        foreach ($sample_files as $sample_file) {
            $file_prefix = 'meta-data/' . $user . '/';
            $sample_file = str_replace($file_prefix, '', $sample_file);
            array_push($files, $sample_file);
        }
        $library_strategies = array('WGA', 'WGS', 'WXS', 'RNA-Seq', 'miRNA-Seq', 'WCS', 'CLONE', 'POOLCLONE', 'AMPLICON', 'FINISHING', 'CLONEEND', 'CHIP-Seq', 'MNase-Seq', 'DNase-Hypersensitivity', 'Bisulfite-Seq', 'Tn-Seq', 'EST', 'FL-cDNA', 'CTS', 'MRE-Seq', 'MeDIP-Seq', 'MBD-Seq', 'Synthetic-Long-Read', 'ATAC-Seq', 'ChIA-PET', 'FAIRE-seq', 'Hi-C', 'ncRNA-Seq', 'RAD-Seq', 'RIP-Seq', 'SELEX', 'ssRNA-seq', 'Targeted-Capture', 'Tethered Chromation Conformation Capture', 'OTHER');
        $library_sources = array('GENOMIC', 'TRANSCRIPTOMIC', 'METAGENOMIC', 'METATRANSCRIPTOMIC', 'SYNTHETIC', 'VIRAL RNA', 'GENOMIC SINGLE CELL', 'TRANSCRIPTOMIC SINGLE CELL', 'OTHER');
        $library_selections = array('RANDOM', 'PCR', 'RANDOM PCR', 'HMPR', 'MF', 'CF-S', 'CF-M', 'CF-H', 'CF-T', 'MDA', 'MSLL', 'cDNA', 'CHIP', 'MNase', 'DNAse', 'Hybrid Selection', 'Reduced Representation', 'Restriction Digest', '5-methylcytidine antibody', 'MBD2 protein methyl-CpG binding domain', 'CAGE', 'RACE', 'size fractionation', 'Padlock probes capture method', 'other', 'unspecified', 'cDNA_oligo_dT', 'cDNA_randomPriming', 'Oligo-dT', 'PolyA', 'repeat fractionation');
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            // Sample update input validate
            $this->validate($request, [
                'sample_label' => 'required|max:50',
                'library_id' => 'required|max:150',
                'library_strategy' => 'required',
                'library_source' => 'required',
                'library_selection' => 'required',
                'platform' => 'required',
                'instrument_model' => 'required',
                'design_description' => 'required|max:500',
                'select_application' => 'required',
                'select_species' => 'nullable',
                'isPairends' => 'required'
            ]);
            $projectID = $request->input('projectID');
            $sample_label = $request->input('sample_label');
            $library_id = $request->input('library_id');
            $library_strategy = $request->input('library_strategy');
            $library_source = $request->input('library_source');
            $library_selection = $request->input('library_selection');
            $platform = $request->input('platform');
            $instrument_model = $request->input('instrument_model');
            $design_description = $request->input('design_description');
            $select_application = $request->input('select_application');
            $select_species = $request->input('select_species');
            switch ($request->input('isPairends')) {
                case 'Single':
                    $isPairends = 0;
                    break;
                case 'Paired-end':
                    $isPairends = 1;
                    break;
            }
            $sample['sampleLabel'] = $sample_label;
            $sample['library_id'] = $library_id;
            $sample['library_strategy'] = $library_strategy;
            $sample['library_source'] = $library_source;
            $sample['library_selection'] = $library_selection;
            $sample['platform'] = $platform;
            $sample['instrument_model'] = $instrument_model;
            $sample['design_description'] = $design_description;
            $sample['applications_id'] = $select_application;
            $sample['species_id'] = $select_species;
            $sample['pairends'] = $isPairends;
            $sample->save();
            if ($request->input('from')) {
                return redirect('/workspace/samples?projectID=' . $projectID);
            } else {
                return redirect('/samples?projectID=' . $projectID);
            }
        }
        return view('Samples.samp_update', ['applications' => $applications, 'all_species' => $all_species, 'sample' => $sample, 'app' => $app, 'base_path' => $base_path, 'library_strategies' => $library_strategies, 'library_sources' => $library_sources, 'library_selections' => $library_selections, 'files' => $files]);
    }

    public function upload(Request $request)
    {
        $projectID = $request->input('projectID');
        if ($request->file('sample_file')->isValid()) {
            $filename = $projectID . '_samples_temp.xlsx';
            $request->file('sample_file')->storeAs('', $filename);
            $file_array = Excel::toArray(new SamplesImport, $filename);
            $file_array = $file_array[0];
            array_shift($file_array);
            foreach ($file_array as $array) {
                Samples::create([
                    'sampleLabel' => $array[0],
                    'library_id' => $array[1],
                    'library_strategy' => $array[2],
                    'library_source' => $array[3],
                    'library_selection' => $array[4],
                    'pairends' => $array[5] == 'paired' ? true : false,
                    'platform' => $array[6],
                    'instrument_model' => $array[7],
                    'design_description' => $array[8],
                    'filetype' => $array[9],
                    'applications_id' => Applications::where('name', $array[10])->value('id'),
                    'projects_id' => $projectID,
                    'species_id' => Species::where('name', $array[11])->value('id'),
                    'filename1' => $array[12],
                    'filename2' => $array[13],
                    'isPrepared' => 0,
                    'status' => 0
                ]);
                MvSamples::dispatch($projectID, $array[12], $array[13])->onQueue('MvSamples');
            }
            Storage::delete($filename);
            return response()->json(['code' => 200]);
        }
    }

    public function download()
    {
        return response()->download(storage_path('Sample_template.xlsx'));
    }

    /**
     *
     */
    // public function search(Request $request)
    // {
    //     $projectID = $request->input('projectID');
    //     $identity = $request->input('identity');
    //     $lab_id = Projects::where('id', $projectID)->value('labs_id');
    //     $user = Labs::where('id', $lab_id)->value('principleInvestigator');
    //     $user = User::where('name', $user)->value('name');
    //     $sample_files =  Storage::disk('local')->exists('meta-data/' . $user) ? Storage::files('meta-data/' . $user) : array();
    //     $files = array();
    //     $all_files = array();
    //     foreach ($sample_files as $sample_file) {
    //         $file_prefix = 'meta-data/' . $user . '/';
    //         $sample_file = str_replace($file_prefix, '', $sample_file);
    //         if (strpos($sample_file, $identity) !== false) {
    //             array_push($files, $sample_file);
    //         }
    //         array_push($all_files, $sample_file);
    //     }
    //     empty($identity) ? $tag = true : $tag = false;
    //     return response()->json(['code' => 200, 'files' => $files, 'all_files' => $all_files, 'tag' => $tag]);
    // }
}
