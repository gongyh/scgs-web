<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
                    $isPI = Labs::where('principleInvestigator', $user->email)->get();
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
            $Projects = Projects::orderBy('id','desc')->paginate(5);
            $current_page = $request->input('page');
            $pageSize = 5;
            try {
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->email)->get();
                    $isAdmin = $user->email == env('ADMIN_EMAIL');
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin', 'current_page','pageSize'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Projects.projects', compact('Projects', 'isPI', 'isAdmin', 'current_page','pageSize'));
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
        $types = array('Marine','Skin','Gut','Oral','Freshwater','Soil','Building','Non_mammal_animal','Other_humanbodysite','Nose','Urogenital','Mammal_animal','Plant','River','Lake','Other_animal','Food','Sand','Milk');
        $last_accession = DB::table('projects')->orderBy('id','desc')->first()->doi;
        \preg_match('/CRP0+/',$last_accession,$dir);
        $accession_number = str_replace($dir[0],'',$last_accession);
        $accession_number = \number_format($accession_number);
        $accession_number += 1;
        $access_num = \str_pad($accession_number,8,0,STR_PAD_LEFT);
        $new_accession = "CRP" . $access_num;
        if ($request->isMethod('POST')) {
            $user = Auth::user();
            $isAdmin = $user->email == env('ADMIN_EMAIL');
            $labId = $request->input('selectLab');
            $isPI = Labs::where([['id', $labId], ['principleInvestigator', $user->email]])->get()->count() > 0;
            if ($request->input('labID')) {
                $this->validate($request, [
                    'new_proj_name' => 'required',
                    'new_proj_desc' => 'required|max:2000',
                    'new_type' => 'required|max:250',
                    'new_collection_date' => 'required|max:250',
                    'new_release_date' => 'required|max:250',
                    'new_location' => 'required|max:250'
                ]);
                $new_proj_name = $request->input('new_proj_name');
                $new_proj_desc = $request->input('new_proj_desc');
                $new_type = $request->input('new_type');
                $new_collection_date = $request->input('new_collection_date');
                $new_release_date = $request->input('new_release_date');
                $new_location = $request->input('new_location');
                $labID = $request->input('labID');
                Projects::create([
                    'labs_id' => $labID,
                    'name' => $new_proj_name,
                    'doi' => $new_accession,
                    'type' => $new_type,
                    'collection_date' => $new_collection_date,
                    'release_date' => $new_release_date,
                    'location' => $new_location,
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
                    'new_proj_desc' => 'required|max:2000',
                    'new_type' => 'required|max:250',
                    'new_collection_date' => 'required|max:250',
                    'new_release_date' => 'required|max:250',
                    'new_location' => 'required|max:250'
                ]);
                $labId = $request->input('selectLab');
                $new_proj_name = $request->input('new_proj_name');
                $new_proj_desc = $request->input('new_proj_desc');
                $new_type = $request->input('new_type');
                $new_collection_date = $request->input('new_collection_date');
                $new_release_date = $request->input('new_release_date');
                $new_location = $request->input('new_location');
                Projects::create([
                    'labs_id' => $labId,
                    'name' => $new_proj_name,
                    'doi' => $new_accession,
                    'type' => $new_type,
                    'collection_date' => $new_collection_date,
                    'release_date' => $new_release_date,
                    'location' => $new_location,
                    'description' => $new_proj_desc
                ]);
                if ($request->input('from')) {
                    return redirect('/workspace/myProject');
                } else {
                    return redirect('/projects');
                }
            } else {
                $pi_error = 'sorry! you are not the principleInvestgator! Can not create project!';
                return view('Projects.proj_create', ['pi_error' => $pi_error, 'labs' => $labs,'types' => $types]);
            }
        }
        if ($request->input('labID')) {
            return view('Projects.proj_create',compact('types'));
        } else {
            return view('Projects.proj_create', compact('types','labs'));
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
        $types = array('Marine','Skin','Gut','Oral','Freshwater','Soil','Building','Non_mammal_animal','Other_humanbodysite','Nose','Urogenital','Mammal_animal','Plant','River','Lake','Other_animal','Food','Sand','Milk');
        if ($request->isMethod('POST')) {
            $input = $request->all();
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'description' => [
                    'required',
                    'max:2000',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'type' => [
                    'required',
                    'max:250',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'collection_date' => [
                    'required',
                    'max:250',
                    Rule::unique('projects')->ignore($proj_id)
                ],
                'location' => [
                    'required',
                    'max:250',
                    Rule::unique('projects')->ignore($proj_id)
                ]
            ])->validate();
            $new_proj = $input['name'];
            $new_desc = $input['description'];
            $new_type = $input['type'];
            $new_collection_date = $input['collection_date'];
            $new_release_date = $input['release_date'];
            $new_location = $input['location'];
            $project['name'] = $new_proj;
            $project['description'] = $new_desc;
            $project['type'] = $new_type;
            $project['collection_date'] = $new_collection_date;
            $project['release_date'] = $new_release_date;
            $project['location'] = $new_location;
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
        return view('Projects.proj_update', ['project' => $project,'types' => $types]);
    }
}
