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
            $new_instiname = $request->input('new-instiname');
            try {
                $institution['name'] = $new_instiname;
                if ($institution->save()) {
                    return redirect('/institutions');
                }
                return redirect('/institutions');
            } catch (\Illuminate\Database\QueryException $ex) {
                return 'Sorry!You have not input the institution name!';
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
}
