<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Institutions;
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
            $current_page = null;
            return view('Institutions.institutions', ['institutions' => $institutions, 'current_page' => $current_page]);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            // institutions validate
            $this->validate($request, [
                'new_institution_name' => 'required|unique:institutions,name|max:250'
            ]);
            $new_institution_name = $request->input('new_institution_name');
            Institutions::create([
                'name' => $new_institution_name
            ]);
            return redirect('/institutions');
        }
        return view('Institutions.insti_create');
    }

    public function delete(Request $request)
    {
        $insti_id = $request->input('instiID');
        $current_page = $request->input('page');
        $institution = Institutions::find($insti_id);
        $institution->delete();
        return redirect('/institutions?page=' . $current_page);
    }

    public function update(Request $request)
    {
        $insti_id = $request->input('instiID');
        $institution = Institutions::find($insti_id);
        $current_page = $request->input('page');
        if ($request->isMethod('post')) {
            $input = $request->all();
            // institutions validate
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('institutions')->ignore($input['sintiID'])
                ]
            ])->validate();
            $institution_name = $request->input('name');
            $institution['name'] = $institution_name;
            $institution->save();
            return redirect('/institutions?page=' . $current_page);
        }
        return view('Institutions.insti_update', ['institution' => $institution]);
    }
}
