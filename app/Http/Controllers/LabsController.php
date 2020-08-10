<?php

namespace App\Http\Controllers;

use App\Labs;
use App\Institutions;
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
        if ($request->isMethod('POST')) {
            try {
                $search_lab = $request->input('search_lab');
                $findLabs = Labs::where('name', 'LIKE', '%' . $search_lab . '%')->paginate(15);
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == 'admin@123.com';
                    return view('Labs.labs', compact('findLabs', 'isPI', 'isAdmin'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Labs.labs', compact('findLabs', 'isPI', 'isAdmin'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $findLabs = null;
                return view('Labs.labs', compact('findLabs'));
            }
        } else {
            $Labs = Labs::paginate(15);
            try {
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == 'admin@123.com';
                    return view('Labs.labs', compact('Labs', 'isPI', 'isAdmin'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Labs.labs', compact('Labs', 'isPI', 'isAdmin'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $selectLabs = null;
                return view('Labs.labs', compact('Labs'));
            }
        }
    }

    public function update(Request $request)
    {
        $labID = $request->input('labID');
        $current_page = ceil($labID / 15);
        $lab = Labs::findOrFail($labID);
        if ($request->isMethod('POST')) {
            $new_labname = $request->input('new-labname');
            try {
                $lab['name'] = $new_labname;
                $lab->save();
                if ($request->input('pos')) {
                    return redirect('/workspace/myLab');
                } else {
                    return redirect('/labs?page=' . $current_page);
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                return 'Sorry!You have not input the lab name!';
            }
        }
        return view('Labs.labs_update', ['lab' => $lab]);
    }

    public function delete(Request $request)
    {
        $lab_id = $request->input('labID');
        $current_page = ceil($lab_id / 15);
        $lab = Labs::find($lab_id);
        $lab->delete();
        if ($request->input('pos')) {
            return redirect('/workspace/myLab');
        } else {
            return redirect('/labs?page=' . $current_page);
        }
    }

    public function create(Request $request)
    {
        $institutions = Institutions::all();
        $user = Auth::user();
        $pi = $user->name;

        if ($request->isMethod('POST')) {
            $new_lab_name = $request->input('new_lab_name');
            if ($request->input('selectInstitution') != "Choose a institution") {
                try {
                    $chose_insti_id = $request->input('selectInstitution');
                    Labs::Create([
                        'name' => $new_lab_name,
                        'principleInvestigator' => $pi,
                        'institutions_id' => $chose_insti_id
                    ]);
                    if ($request->input('pos')) {
                        return redirect('/workspace/myLab');
                    } else {
                        return redirect('/labs');
                    }
                } catch (\Illuminate\Database\QueryException $ex) {
                    $error = 'You haven\'t input lab name';
                    return view('Labs.labs_create', ['institutions' => $institutions, 'error' => $error]);
                }
            } else {
                $error = 'choose a institution first';
                return view('Labs.labs_create', ['institutions' => $institutions, 'error' => $error]);
            }
        }
        return view('Labs.labs_create', ['institutions' => $institutions]);
    }
}
