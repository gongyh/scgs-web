<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Projects;
use App\Samples;
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

    public function myLab()
    {
        $user = Auth::user();
        try {
            $myLabs = Labs::where('principleInvestigator', $user->name)->orderBy('id','desc')->paginate(15);
            return view('Workspace.myLab', ['myLabs' => $myLabs]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $myLabs = null;
            return view('Workspace.myLab', ['myLabs' => $myLabs]);
        }
    }

    public function myProject()
    {
        try {
            $user = Auth::user();
            $myLabs = Labs::where('principleInvestigator', $user->name)->get('id');
            $lab_id_list = array();
            foreach ($myLabs as $myLab) {
                array_push($lab_id_list, $myLab->id);
            }
            $myProjects = Projects::whereIn('labs_id', $lab_id_list)->orderBy('id','desc')->paginate(5);
            return view('Workspace.myProject', ['myProjects' => $myProjects]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $myProjects = null;
            return view('Workspace.myProject', ['myProjects' => $myProjects]);
        }
    }

    public function selectMyProj(Request $request)
    {
        try {
            $current_page = $request->input('current_page');
            $labID = $request->input('labID');
            $selectMyProjs = Projects::where('labs_id', $labID)->orderBy('id','desc')->paginate(5);
            return view('Workspace.selectMyProj', ['selectMyProjs' => $selectMyProjs, 'labID' => $labID, 'current_page' => $current_page]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectMyProjs = null;
            return view('Workspace.selectMyProj', ['selectMyProjs' => $selectMyProjs, 'labID' => $labID]);
        }
    }

    public function addSamples(){
        return view('Workspace.addSampleFiles');
    }

    public function addSampleFiles(Request $request){
        $user = Auth::user();
        $username = $user->name;
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $mkdir = 'if [ ! -d "' . $base_path .  '/meta-data/' . $username . '" ]; then mkdir -p ' . $base_path . '/meta-data/' . $username . '; fi';
        system($mkdir);
        $storage_path = 'meta-data/' . $username;
        if($request->file('addSampleFiles')->isValid()){
            $file = $request->file('addSampleFiles');
            $fileName = $file->getClientOriginalName();
            $file->storeAs($storage_path,$fileName,'local');
            return response()->json(['code'=>200,'data'=>$file]);
        }
    }
}
