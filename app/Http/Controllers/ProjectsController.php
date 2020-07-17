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
    try{
        $this->authorize('delete-update-control');
        $project = Projects::find($id);
        if($request->isMethod('POST')){
        $new_proj = $request->input('new-projname');
        $project['name'] = $new_proj;
        if($project->save()){
            return redirect('/projects');
        }
      }
      return view('proj_update',['project'=>$project]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
        return 'sorry!can not update!';
    }
  }

  public function delete($id){
    $this->authorize('delete-update-control');
    $project = Projects::find($id);
    $project->delete();
    return redirect('/projects');
  }
}
