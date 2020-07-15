<?php

namespace App\Http\Controllers;

use App\Projects;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = Projects::paginate(15);
        return view('projects', ['projects'=>$projects]);
    }

    public function update(Request $request,$id)
    {
        $project = Projects::find($id);
        if($request->isMethod('post')){
            $new_proj = $request->input('new-projname');
            $project['name'] = $new_proj;
            if($project->save()){
                return redirect('/projects');
            }
        }
        return view('proj_update',['project'=>$project]);
    }

    public function delete($id){
        $project = Projects::find($id);
        if($project->delete()){
            return redirect('/projects');
        }
    }
}
