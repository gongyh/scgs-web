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
        $selectLabs = Labs::where('institutions_id', $instiID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['institutions_id', $instiID], ['principleInvestigator', $user->name]])->get();
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
        $instiID = $request->input('instiID');
        $labID = $request->input('labID');
        $lab = Labs::findOrFail($labID);
        if ($request->isMethod('POST')) {
            $new_labname = $request->input('new-labname');
            try {
                $lab['name'] = $new_labname;
                $lab->save();
                return redirect('/labs?instiID=' . $instiID);
            } catch (\Illuminate\Database\QueryException $ex) {
                return 'Sorry!You have not input the lab name!';
            }
        }
        return view('Labs.labs_update', ['lab' => $lab]);
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
                'institutions_id' => $instiID
            ]);
            return redirect('/labs?instiID=' . $instiID);
        }
        return view('Labs.labs_create');
    }
}
