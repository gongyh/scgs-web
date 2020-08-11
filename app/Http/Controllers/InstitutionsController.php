<?php

namespace App\Http\Controllers;

use App\Institutions;
use Illuminate\Support\Facades\Auth;
use App\Labs;
use Illuminate\Http\Request;

class InstitutionsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $institutions = Institutions::paginate(15);
            $current_page = $request->input('page');
            return view('Institutions.institutions', ['institutions' => $institutions, 'current_page' => $current_page]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $institutions = null;
            return view('Institutions.institutions', ['institutions' => $institutions, 'current_page' => $current_page]);
        }
    }

    public function update(Request $request)
    {
        $insti_id = $request->input('instiID');
        $institution = Institutions::find($insti_id);
        $current_page = $request->input('page');
        if ($request->isMethod('post')) {
            $new_instiname = $request->input('new-instiName');
            try {
                $institution['name'] = $new_instiname;
                $institution->save();
                return redirect('/institutions?page=' . $current_page);
                return redirect('/institutions');
            } catch (\Illuminate\Database\QueryException $ex) {
                $error = 'Institution\'s name illegal';
                return view('Institutions.insti_create', ['error' => $error]);
            }
        }
        return view('Institutions.insti_update', ['institution' => $institution]);
    }

    public function delete(Request $request)
    {
        $insti_id = $request->input('instiID');
        $current_page = $request->input('page');
        $institution = Institutions::find($insti_id);
        $institution->delete();
        return redirect('/institutions?page=' . $current_page);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            try {
                $new_insti_name = $request->input('new_insti_name');
                Institutions::create([
                    'name' => $new_insti_name
                ]);
                return redirect('/institutions');
            } catch (\Illuminate\Database\QueryException $ex) {
                $error = 'Institution\'s name illegal';
                return view('Institutions.insti_create', ['error' => $error]);
            }
        }
        return view('Institutions.insti_create');
    }
}
