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
    public function index()
    {
        $institutions = Institutions::paginate(15);
        if (auth::check()) {
            $user = Auth::user();
            $isAdmin = $user->email == 'admin@123.com';
            return view('Institutions.institutions', ['institutions' => $institutions, 'isAdmin' => $isAdmin]);
        } else {
            $isAdmin = false;
            return view('Institutions.institutions', ['institutions' => $institutions, 'isAdmin' => $isAdmin]);
        }
    }

    public function update(Request $request, $id)
    {
        $institution = Institutions::find($id);
        if ($request->isMethod('post')) {
            $new_instiname = $request->input('new-stiname');
            $institution['name'] = $new_instiname;

            if ($institution->save()) {
                return redirect('/institutions');
            }
        }
        return view('Institutions.insti_update', ['institution' => $institution]);
    }

    public function delete($id)
    {
        $institution = Institutions::find($id);
        $institution->delete();
        return redirect('/institutions');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $new_insti_name = $request->input('new_insti_name');
            Institutions::create([
                'name' => $new_insti_name
            ]);
            return redirect('/institutions');
        }
        return view('Institutions.insti_create');
    }

    public function next(Request $request)
    {
        $instiID = $request->input('instiID');
        $selectLabs = Labs::where('institution_id', $instiID)->paginate(15);
        try {
            if (auth::check()) {
                $user = Auth::user();
                $isPI = Labs::where([['institution_id', $instiID], ['principleInvestigator', $user->name]])->get();
                $isAdmin = $user->email == 'admin@123.com';
                return view('Labs.tolab', compact('selectLabs', 'isPI', 'isAdmin', 'instiID'));
            } else {
                $isPI  = collect();
                $isAdmin = false;
                return view('Labs.tolab', compact('selectLabs', 'isPI', 'isAdmin', 'instiID'));
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectLabs = null;
            return view('Labs.tolab', compact('selectLabs', 'instiID'));
        }
    }
}
