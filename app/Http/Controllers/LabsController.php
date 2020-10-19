<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
                $current_page = $request->input('page');
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == env('ADMIN_EMAIL');
                    return view('Labs.labs', compact('findLabs', 'isPI', 'isAdmin', 'current_page'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Labs.labs', compact('findLabs', 'isPI', 'isAdmin', 'current_page'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                // 未找到labs时显示
                $findLabs = null;
                return view('Labs.labs', compact('findLabs'));
            }
        } else {
            $Labs = Labs::paginate(15);
            $current_page = $request->input('page');
            try {
                if (auth::check()) {
                    $user = Auth::user();
                    $isPI = Labs::where('principleInvestigator', $user->name)->get();
                    $isAdmin = $user->email == env('ADMIN_EMAIL');
                    return view('Labs.labs', compact('Labs', 'isPI', 'isAdmin', 'current_page'));
                } else {
                    $isPI  = collect();
                    $isAdmin = false;
                    return view('Labs.labs', compact('Labs', 'isPI', 'isAdmin', 'current_page'));
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                // 数据库中没有labs时显示
                $Labs = null;
                return view('Labs.labs', compact('Labs'));
            }
        }
    }

    public function create(Request $request)
    {
        $institutions = Institutions::all();
        $user = Auth::user();
        $pi = $user->name;

        if ($request->isMethod('POST')) {
            // labs create validate
            $this->validate($request, [
                'choose_a_institution' => 'required',
                'new_lab_name' => 'required|max:250'
            ]);
            $new_lab_name = $request->input('new_lab_name');
            $choose_a_institution = $request->input('choose_a_institution');
            Labs::Create([
                'name' => $new_lab_name,
                'principleInvestigator' => $pi,
                'institutions_id' => $choose_a_institution
            ]);
            if ($request->input('from')) {
                return redirect('/workspace/myLab');
            } else {
                return redirect('/labs');
            }
        }
        return view('Labs.labs_create', ['institutions' => $institutions]);
    }

    public function delete(Request $request)
    {
        $lab_id = $request->input('labID');
        $current_page = $request->input('page');
        $lab = Labs::find($lab_id);
        $lab->delete();
        if ($request->input('from')) {
            return redirect('/workspace/myLab');
        } else {
            return redirect('/labs?page=' . $current_page);
        }
    }

    public function update(Request $request)
    {
        $labID = $request->input('labID');
        $current_page = $request->input('page');
        $lab = Labs::findOrFail($labID);
        if ($request->isMethod('POST')) {
            $input = $request->all();
            // labs update validate
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('labs')->ignore($input['labID'])
                ]
            ])->validate();
            $lab_name = $request->input('name');
            $lab['name'] = $lab_name;
            $lab->save();
            if ($request->input('from')) {
                return redirect('/workspace/myLab');
            } else {
                return redirect('/labs?page=' . $current_page);
            }
        }
        return view('Labs.labs_update', ['lab' => $lab]);
    }
}
