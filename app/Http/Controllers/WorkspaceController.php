<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Projects;
use App\Samples;
use Illuminate\Http\Request;
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
            $myLabs = Labs::where('principleInvestigator', $user->name)->paginate(15);
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
            $myProjects = Projects::whereIn('labs_id', $lab_id_list)->paginate(5);
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
            $selectMyProjs = Projects::where('labs_id', $labID)->paginate(5);
            return view('Workspace.selectMyProj', ['selectMyProjs' => $selectMyProjs, 'labID' => $labID, 'current_page' => $current_page]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectMyProjs = null;
            return view('Workspace.selectMyProj', ['selectMyProjs' => $selectMyProjs, 'labID' => $labID]);
        }
    }
}
