<?php

namespace App\Http\Controllers;

use App\Projects;
use App\Labs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $labID = $request->input('labID');
        $current_insti_id = Labs::where('id', $labID)->value('institutions_id');
        $selectProjects = Projects::where('labs_id', $labID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $labID], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $isAdmin = $user->email == 'admin@123.com';
                return view('Projects.projects', compact('selectProjects', 'isPI', 'isAdmin', 'labID', 'current_insti_id'));
            } else {
                $isPI  = false;
                $isAdmin = false;
                return view('Projects.projects', compact('selectProjects', 'isPI', 'isAdmin', 'labID', 'current_insti_id'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectLabs = null;
            return view('Labs.labs', compact('selectLabs', 'labID', 'current_insti_id'));
        }
    }

    public function update(Request $request)
    {
        try {
            // $this->authorize('delete-update-control');
            $labID = $request->input('labID');
            $proj_id = $request->input('projectID');
            $project = Projects::find($proj_id);
            if ($request->isMethod('POST')) {
                $new_proj = $request->input('new_projName');
                $new_doi = $request->input('new_projDoi');
                $new_desc = $request->input('new_projDesc');
                try {
                    $project['name'] = $new_proj;
                    $project['doi'] = $new_doi;
                    $project['description'] = $new_desc;
                    $project->save();
                    return redirect('/projects?labID=' . $labID);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return 'Sorry!You have not input the sample label!';
                }
            }
            return view('Projects.proj_update', ['project' => $project]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return 'sorry!can not update!';
        }
    }

    public function delete(Request $request)
    {
        // $this->authorize('delete-update-control');
        $proj_id = $request->input('projectID');
        $lab_id = $request->input('labID');
        $project = Projects::find($proj_id);
        $project->delete();
        return redirect('/projects?labID=' . $lab_id);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $labID = $request->input('labID');
            $new_proj_name = $request->input('new_proj_name');
            $new_doi_num = $request->input('new_doi_num');
            $new_proj_desc = $request->input('new_proj_desc');
            try{
                Projects::create([
                    'labs_id' => $labID,
                    'name' => $new_proj_name,
                    'doi' => $new_doi_num,
                    'description' => $new_proj_desc
                ]);
                return redirect('/projects?labID=' . $labID);
            }catch(\Illuminate\Database\QueryException $ex){
                return 'Sorry!You have not input the sample label!';
            }
        }
        return view('Projects.proj_create');
    }

}
