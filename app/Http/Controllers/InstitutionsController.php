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
        $user = Auth::user();
        $isPI = Labs::where('PrincipleInvestigator', $user->name)->get()->count() > 0;
        $institutions = Institutions::paginate(15);
        return view('Institutions.institutions', ['institutions' => $institutions, 'isPI' => $isPI]);
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

    public function next(Request $request)
    {
        $instiID = $request->input('instiID');
        try {
            $selectLabs = Labs::where('institution_id', $instiID)->paginate(15);
            $user = Auth::user();
            $isPI = Labs::where('PrincipleInvestigator', $user->name)->get()->count() > 0;
            return view('Labs.tolab', ['selectLabs' => $selectLabs, 'isPI' => $isPI]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $selectLabs = null;
            return view('Labs.tolab', ['selectLabs' => $selectLabs]);
        }
    }
}
