<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Jobs;
use App\Projects;
use App\Samples;
use App\Weblog;
use App\Ncbiupload;
use App\Jobs\NCBIDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    //
    public function index()
    {
        return view('Workspace.workspace');
    }

    public function myLab(Request $request)
    {
        $user = Auth::user();
        try {
            $myLabs = Labs::where('principleInvestigator', $user->name)->orderBy('id','desc')->paginate(15);
            $current_page = $request->input('page');
            $pageSize = 15;
            return view('Workspace.myLab', compact('myLabs','current_page','pageSize'));
        } catch (\Illuminate\Database\QueryException $ex) {
            $myLabs = null;
            return view('Workspace.myLab', compact('myLabs'));
        }
    }

    public function myProject(Request $request)
    {
        $current_url = url()->full();
        if(strpos($current_url,'myLab') != false){
            try {
                $current_page = $request->input('current_page');
                $labID = $request->input('labID');
                $myProjects = Projects::where('labs_id', $labID)->orderBy('id','desc')->paginate(5);
                $myProjects->withPath('/workspace/myLab/projects?labID=' . $labID);
                $pageSize = 5;
                return view('Workspace.myProject', ['myProjects' => $myProjects, 'labID' => $labID, 'current_page' => $current_page,'pageSize' => $pageSize]);
            } catch (\Illuminate\Database\QueryException $ex) {
                $myProjects = null;
                return view('Workspace.myProject', ['myProjects' => $myProjects, 'labID' => $labID,'current_page' => $current_page]);
            }
        }else{
            try {
                $user = Auth::user();
                $current_page = $request->input('page');
                $myLabs = Labs::where('principleInvestigator', $user->name)->get('id');
                $lab_id_list = array();
                foreach ($myLabs as $myLab) {
                    array_push($lab_id_list, $myLab->id);
                }
                $myProjects = Projects::whereIn('labs_id', $lab_id_list)->orderBy('id','desc')->paginate(5);
                $pageSize = 5;
                return view('Workspace.myProject', ['myProjects' => $myProjects,'current_page' => $current_page,'pageSize' => $pageSize]);
            } catch (\Illuminate\Database\QueryException $ex) {
                $myProjects = null;
                return view('Workspace.myProject', ['myProjects' => $myProjects,'current_page'=>$current_page]);
            }
        }
    }

    public function addSamples(Request $request)
    {
        if($request->isMethod('POST')){
            if ($request->hasFile('sra_id_file') && $request->file('sra_id_file')->isValid()) {
                $username = Auth::user()->name;
                $filename = $username . 'sra.txt';
                $request->file('sra_id_file')->storeAs('', $filename);
                $file_data = Storage::get($filename);
                $file_array = explode("\r\n", $file_data);
                $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
                foreach($file_array as $file_arr){
                    if(Storage::disk('local')->exists('meta-data/public/' . $file_arr . '_1.fastq.gz')){
                        $command = 'cp ' . $base_path . 'meta-data/public/' . $file_arr . '_1.fastq.gz ' .
                        $base_path . 'meta-data/' . $username . ' && cp ' . $base_path . 'meta-data/public/' . $file_arr . '_2.fastq.gz ' . $base_path . 'meta-data/' . $username;
                        system($command);
                        continue;
                    }
                    Ncbiupload::create([
                        'sra_id' => $file_arr,
                        'isPrepared' => false,
                        'uuid' => 'default',
                        'user' => $username
                    ]);
                    NCBIDownload::dispatch($username, $file_arr)->onQueue('NCBIDownload');
                }
                Storage::delete($filename);
                return redirect('/workspace/addSampleFiles');
            }else{
                $this->validate($request, [
                    'ncbi_sra_id' => 'required',
                ]);
                $user = Auth::user();
                $username = $user->name;
                $sra_id = $request->input('ncbi_sra_id');
                $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
                if(Storage::disk('local')->exists('meta-data/public' . '/' . $sra_id . '_1.fastq.gz')){
                    $command = 'cp '. $base_path . 'meta-data/public/' . $sra_id . '_1.fastq.gz ' .
                    $base_path . 'meta-data/' . $username. ' && cp ' . $base_path . 'meta-data/public/' . $sra_id . '_2.fastq.gz ' . $base_path . 'meta-data/' . $username;
                    system($command);
                    return redirect('/workspace/addSampleFiles');
                }
                Ncbiupload::create([
                    'sra_id' => $sra_id,
                    'isPrepared' => false,
                    'uuid' => 'default',
                    'user' => $username
                ]);
                NCBIDownload::dispatch($username, $sra_id)->onQueue('NCBIDownload');
                return redirect('/workspace/addSampleFiles');
            }
        }
        $user = Auth::user()->name;
        $file_lists = Storage::files('meta-data/' . $user);
        $fileList = array();
        foreach($file_lists as $file_list){
            $file_list = str_replace("meta-data/" . $user . "/" , "" , $file_list);
            array_push($fileList , $file_list);
        }
        return view('Workspace.addSampleFiles', compact('fileList'));
    }

    public function addSampleFiles(Request $request)
    {
        $user = Auth::user();
        $username = $user->name;
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $mkdir = 'if [ ! -d "' . $base_path .  '/meta-data/' . $username . '" ]; then mkdir -p ' . $base_path . '/meta-data/' . $username . '; fi';
        system($mkdir);
        $storage_path = 'meta-data/' . $username;
        if($request->hasFile('addSampleFiles') && $request->file('addSampleFiles')->isValid()){
            $file = $request->file('addSampleFiles');
            $fileName = $file->getClientOriginalName();
            $file->storeAs($storage_path, $fileName, 'local');
            return response()->json(['code' => 200]);
        }
    }

    public function manageRunning()
    {
        $samples = new Samples();
        $projects = new Projects();
        $now = time();
        $running_jobs = Jobs::where('status', 1)->get();
        return view('Workspace.manageRunning', compact('now','running_jobs','samples','projects'));
    }

    public function runningTerminate(Request $request)
    {
        $uuid = $request->input('uuid');
        $job_id = Jobs::where('current_uuid',$uuid)->value('id');
        $job = Jobs::find($job_id);
        $job->status = 2;
        $job->save();
        if($request->input('sampleID')){
            $sampleID = $request->input('sampleID');
            $sample = Samples::find($sampleID);
            $sample->status = 0;
            $sample->save();
        }
        return redirect('/workspace/manageRunning');
    }

    public function ncbifilesList()
    {
        $ncbi_files = Storage::files('meta-data/public');
        return view('Workspace.ncbiFileList', compact('ncbi_files'));
    }

    public function ncbi_download_status()
    {
        $preparing_list = Ncbiupload::where('isPrepared',0)->get();
        $data = array();
        if(isset($preparing_list)){
            foreach($preparing_list as $prepare){
                array_push($data, $prepare->sra_id);
            }
            return response()->json(['code' => 200,'data' => $data]);
        }else{
            $data = array();
            return response()->json(['code' => 200,'data' => $data]);
        }
    }

    public function weblog_clear()
    {
        $jobs = jobs::all();
        $jobs_currentuuids = array();
        foreach($jobs as $job){
            array_push($jobs_currentuuids, $job->current_uuid);
        }
        Weblog::whereNotIn('runName',$jobs_currentuuids)->delete();
        flash('weblog cleaned')->success();
        return back();
    }
}
