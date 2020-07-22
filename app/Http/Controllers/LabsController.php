<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Projects;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LabsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $isPI = Labs::where('PrincipleInvestigator', $user->name)->get()->count() > 0;
        $labs = Labs::paginate(15);
        return view('Labs.labs', compact('labs', 'isPI'));
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('delete-update-control');
            $lab = Labs::findOrFail($id);
            if ($request->isMethod('POST')) {
                $new_labname = $request->input('new-labname');
                $lab['name'] = $new_labname;
                $lab->save();
                return redirect('/labs');
            }
            return view('Labs.labs_update', ['lab' => $lab]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return 'sorry!can not update!';
        }
    }

    public function delete($id)
    {
        $lab = Labs::find($id);
        $lab->delete();
        return redirect('/labs');
    }

    public function next(Request $request)
    {
        $labID = $request->input('labID');
        try {
            $selectProjects = Projects::where('labID', $labID)->paginate(15);
            $user = Auth::user();
            $isPI = Labs::where('PrincipleInvestigator', $user->name)->get()->count() > 0;
            return view('Projects.toprojects', ['selectProjects' => $selectProjects, 'isPI' => $isPI]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectProjects = null;
            return view('Projects.toprojects', ['selectProjects' => $selectProjects]);
        }
    }
}
