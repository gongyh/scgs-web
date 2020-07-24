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
    public function index(Request $request)
    {
        $instiID = $request->input('instiID');
        $selectLabs = Labs::where('institution_id', $instiID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['institution_id', $instiID], ['principleInvestigator', $user->name]])->get();
                $isAdmin = $user->email == 'admin@123.com';
                return view('Labs.labs', compact('selectLabs', 'isPI', 'isAdmin', 'instiID'));
            } else {
                $isPI  = collect();
                $isAdmin = false;
                return view('Labs.labs', compact('selectLabs', 'isPI', 'isAdmin', 'instiID'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectLabs = null;
            return view('Labs.labs', compact('selectLabs', 'instiID'));
        }
    }

    public function update(Request $request)
    {
        try {
            $instiID = $request->input('instiID');
            $labID = $request->input('labID');
            $lab = Labs::findOrFail($labID);
            if ($request->isMethod('POST')) {
                $new_labname = $request->input('new-labname');
                $lab['name'] = $new_labname;
                $lab->save();
                return redirect('institutions/labs?instiID=' . $instiID);
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

    public function create(Request $request)
    {
        $user = Auth::user();
        $pi = $user->name;
        $instiID = $request->input("instiID");
        if ($request->isMethod('POST')) {
            $new_lab_name = $request->input('new_lab_name');
            Labs::create([
                'name' => $new_lab_name,
                'principleInvestigator' => $pi,
                'institution_id' => $instiID
            ]);
            return redirect('/institutions/labs?instiID=' . $instiID);
        }
        return view('Labs.labs_create');
    }

    public function next(Request $request)
    {
        $labID = $request->input('labID');
        try {
            $selectProjects = Projects::where('labID', $labID)->paginate(15);
            $user = Auth::user();
            $isPI = Labs::where('principleInvestigator', $user->name)->get()->count() > 0;
            return view('Projects.toprojects', ['selectProjects' => $selectProjects, 'isPI' => $isPI]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectProjects = null;
            return view('Projects.toprojects', ['selectProjects' => $selectProjects]);
        }
    }
}
