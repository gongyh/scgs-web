<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Projects;
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
        $current_lab_name = Labs::where('id', $labID)->value('name');
        $selectProjects = Projects::where('labs_id', $labID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $labID], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $isAdmin = $user->email == 'admin@123.com';
                return view('Projects.selectProjects', compact('selectProjects', 'isPI', 'isAdmin', 'labID', 'current_lab_name'));
            } else {
                $isPI  = false;
                $isAdmin = false;
                return view('Projects.selectProjects', compact('selectProjects', 'isPI', 'isAdmin', 'labID', 'current_lab_name'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectProjects = null;
            return view('Project.selectProjects', compact('selectLabs', 'current_lab_name'));
        }
    }

    public function update(Request $request)
    {
        $proj_id = $request->input('projectID');
        $project = Projects::find($proj_id);
        if ($request->isMethod('POST')) {
            if ($request->input('labID')) {
                $labID = $request->input('labID');
                $new_proj = $request->input('new_projName');
                $new_doi = $request->input('new_doiNum');
                $new_desc = $request->input('new_projDesc');
                try {
                    $project['name'] = $new_proj;
                    $project['doi'] = $new_doi;
                    $project['description'] = $new_desc;
                    $project->save();
                    return redirect('/projects?labID=' . $labID);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return 'Sorry!You have not input the information!';
                }
            } else {
                $new_proj = $request->input('new_projName');
                $new_doi = $request->input('new_doiNum');
                $new_desc = $request->input('new_projDesc');
                $current_page = ceil($proj_id / 15);
                try {
                    $project['name'] = $new_proj;
                    $project['doi'] = $new_doi;
                    $project['description'] = $new_desc;
                    $project->save();
                    if ($request->input('pos')) {
                        return redirect('/myProject');
                    } else {
                        return redirect('/projects?page=' . $current_page);
                    }
                } catch (\Illuminate\Database\QueryException $ex) {
                    return 'Sorry!You have not input the information!';
                }
            }
        }
        return view('Projects.proj_update', ['project' => $project]);
    }

    public function delete(Request $request)
    {
        if ($request->input('labID') != null) {
            $proj_id = $request->input('projectID');
            $lab_id = $request->input('labID');
            $project = Projects::find($proj_id);
            $project->delete();
            return redirect('/labs/projects?labID=' . $lab_id);
        } else {
            $proj_id = $request->input('projectID');
            $current_page = ceil($proj_id / 15);
            $project = Projects::find($proj_id);
            $project->delete();
            if ($request->input('pos')) {
                return redirect('/myProject');
            } else {
                return redirect('/projects?page=' . $current_page);
            }
        }
    }

    public function create(Request $request)
    {
        $labs = Labs::all();
        if ($request->isMethod('POST')) {
            $user = Auth::user();
            $isAdmin = $user->email == 'admin@123.com';
            $labId = $request->input('selectLab');
            $new_proj_name = $request->input('new_proj_name');
            $new_doi_num = $request->input('new_doi_num');
            $new_proj_desc = $request->input('new_proj_desc');
            $isPI = Labs::where([['id', $labId], ['principleInvestigator', $user->name]])->get()->count() > 0;
            if ($request->input('labID')) {
                try {
                    $labID = $request->input('labID');
                    Projects::create([
                        'labs_id' => $labID,
                        'name' => $new_proj_name,
                        'doi' => $new_doi_num,
                        'description' => $new_proj_desc
                    ]);
                    return redirect('/projects?labID=' . $labID);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return 'Sorry!You have not input all information!';
                }
            } elseif ($request->input('selectLab') == 'choose a lab') {
                $error = 'choose a lab first';
                return view('Projects.proj_create', ['error' => $error, 'labs' => $labs]);
            } elseif ($isPI || $isAdmin && $request->input('labID') == null) {
                try {
                    Projects::create([
                        'labs_id' => $labId,
                        'name' => $new_proj_name,
                        'doi' => $new_doi_num,
                        'description' => $new_proj_desc
                    ]);
                    if ($request->input('pos')) {
                        return redirect('/myProject');
                    } else {
                        return redirect('/projects');
                    }
                } catch (\Illuminate\Database\QueryException $ex) {
                    return 'Sorry!You have not input all information!';
                }
            } else {
                $error = 'sorry! you are not the principleInvestgator! Can not create project!';
                return view('Projects.proj_create', ['error' => $error, 'labs' => $labs]);
            }
        }
        if ($request->input('labID')) {
            return view('Projects.proj_create');
        } else {
            return view('Projects.proj_create', ['labs' => $labs]);
        }
    }

    public function projectList(Request $request)
    {
        if ($request->isMethod('POST')) {
            try {
                $search_project = $request->input('search_project');
                $findProjects = Projects::where('name', 'LIKE', '%' . $search_project . '%')->paginate(15);
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == 'admin@123.com';
                    return view('Projects.projects', compact('findProjects', 'isPI', 'isAdmin'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Projects.projects', compact('findProjects', 'isPI', 'isAdmin'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $findProjects = null;
                return view('Projects.projects', compact('findProjects'));
            }
        } else {
            $Projects = Projects::paginate(15);
            try {
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == 'admin@123.com';
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $Projects = null;
                return view('Projects.projects', compact('Projects'));
            }
        }
    }
}
