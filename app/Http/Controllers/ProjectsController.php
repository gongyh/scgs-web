<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        if ($request->isMethod('POST')) {
            try {
                $search_project = $request->input('search_project');
                $current_page = $request->input('page');
                $findProjects = Projects::where('name', 'LIKE', '%' . $search_project . '%')->paginate(5);
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == env('ADMIN_EMAIL');
                    return view('Projects.projects', compact('findProjects', 'isPI', 'isAdmin', 'current_page'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Projects.projects', compact('findProjects', 'isPI', 'isAdmin', 'current_page'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                //未找到projects时显示
                $findProjects = null;
                return view('Projects.projects', compact('findProjects'));
            }
        } else {
            $Projects = Projects::paginate(5);
            $current_page = $request->input('page');
            try {
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == env('ADMIN_EMAIL');
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin', 'current_page'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin', 'current_page'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                // 数据库中没有projects时显示
                $Projects = null;
                return view('Projects.projects', compact('Projects'));
            }
        }
    }

    public function create(Request $request)
    {
        $labs = Labs::all();
        if ($request->isMethod('POST')) {
            $user = Auth::user();
            $isAdmin = $user->email == env('ADMIN_EMAIL');
            $labId = $request->input('selectLab');
            $isPI = Labs::where([['id', $labId], ['principleInvestigator', $user->name]])->get()->count() > 0;
            if ($request->input('labID')) {
                $this->validate($request, [
                    'new_proj_name' => 'required',
                    'new_project_id' => 'required|unique:projects,doi',
                    'new_proj_desc' => 'required|max:2000'
                ]);
                $new_proj_name = $request->input('new_proj_name');
                $new_project_id = $request->input('new_project_id');
                $new_proj_desc = $request->input('new_proj_desc');
                $labID = $request->input('labID');
                Projects::create([
                    'labs_id' => $labID,
                    'name' => $new_proj_name,
                    'doi' => $new_project_id,
                    'description' => $new_proj_desc
                ]);
                if ($request->input('from')) {
                    return redirect('/workspace/myLab/projects?labID=' . $labID);
                } else {
                    return redirect('/projects?labID=' . $labID);
                }
            } elseif ($isPI || $isAdmin && $request->input('labID') == null) {
                $this->validate($request, [
                    'selectLab' => 'required',
                    'new_proj_name' => 'required',
                    'new_project_id' => 'required|unique:projects,doi',
                    'new_proj_desc' => 'required|max:2000'
                ]);
                $labId = $request->input('selectLab');
                $new_proj_name = $request->input('new_proj_name');
                $new_project_id = $request->input('new_project_id');
                $new_proj_desc = $request->input('new_proj_desc');
                Projects::create([
                    'labs_id' => $labId,
                    'name' => $new_proj_name,
                    'doi' => $new_project_id,
                    'description' => $new_proj_desc
                ]);
                if ($request->input('from')) {
                    return redirect('/workspace/myProject');
                } else {
                    return redirect('/projects');
                }
            } else {
                $pi_error = 'sorry! you are not the principleInvestgator! Can not create project!';
                return view('Projects.proj_create', ['pi_error' => $pi_error, 'labs' => $labs]);
            }
        }
        if ($request->input('labID')) {
            return view('Projects.proj_create');
        } else {
            return view('Projects.proj_create', ['labs' => $labs]);
        }
    }

    public function delete(Request $request)
    {
        if ($request->input('labID') != null) {
            $proj_id = $request->input('projectID');
            $lab_id = $request->input('labID');
            $project = Projects::find($proj_id);
            $project->delete();
            if ($request->input('from')) {
                return redirect('/workspace/myLab/projects?labID=' . $lab_id);
            } else {
                return redirect('/labs/projects?labID=' . $lab_id);
            }
        } else {
            $proj_id = $request->input('projectID');
            $current_page = $request->input('page');
            $project = Projects::find($proj_id);
            $project->delete();
            if ($request->input('from')) {
                return redirect('/workspace/myProject');
            } else {
                return redirect('/projects?page=' . $current_page);
            }
        }
    }

    public function update(Request $request)
    {
        $proj_id = $request->input('projectID');
        $project = Projects::find($proj_id);
        if ($request->isMethod('POST')) {
            $input = $request->all();
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'doi' => [
                    'required',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'description' => [
                    'required',
                    'max:2000',
                    Rule::unique('projects')->ignore($proj_id)
                ]
            ])->validate();
            $new_proj = $input['name'];
            $new_doi = $input['doi'];
            $new_desc = $input['description'];
            $project['name'] = $new_proj;
            $project['doi'] = $new_doi;
            $project['description'] = $new_desc;
            $project->save();
            if ($request->input('labID')) {
                $labID = $request->input('labID');
                if ($request->input('from')) {
                    return redirect('/workspace/myLab/projects?labID=' . $labID);
                } else {
                    return redirect('/labs/projects?labID=' . $labID);
                }
            } else {
                $current_page = $request->input('page');
                if ($request->input('from')) {
                    return redirect('/workspace/myProject');
                } else {
                    return redirect('/projects?page=' . $current_page);
                }
            }
        }
        return view('Projects.proj_update', ['project' => $project]);
    }

    public function selectProj(Request $request)
    {
        $labID = $request->input('labID');
        $selectProjects = Projects::where('labs_id', $labID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['id', $labID], ['principleInvestigator', $user->name]])->get()->count() > 0;
                $isAdmin = $user->email == env('ADMIN_EMAIL');
                return view('Projects.selectProjects', compact('selectProjects', 'isPI', 'isAdmin', 'labID'));
            } else {
                $isPI  = false;
                $isAdmin = false;
                return view('Projects.selectProjects', compact('selectProjects', 'isPI', 'isAdmin', 'labID'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            // 未找到projects时显示
            $selectProjects = null;
            return view('Project.selectProjects', compact('selectLabs'));
        }
    }
}
