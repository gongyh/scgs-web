<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Applications;
use Illuminate\Http\Request;

class ApplicationsController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $applications = Applications::paginate(15);
            $current_page = $request->input('page');
            return view('Applications.applications', ['applications' => $applications, 'current_page' => $current_page]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $applications = null;
            $current_page = null;
            return view('Applications.applications', ['applications' => $applications, 'current_page' => $current_page]);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            //Applications validate
            $this->validate($request, [
                'new_app_name' => 'required|unique:applications,name|max:250',
                'new_app_desc' => 'required|unique:applications,description|max:250'
            ]);
            $new_app_name = $request->input('new_app_name');
            $new_app_desc = $request->input('new_app_desc');
            Applications::create([
                'name' => $new_app_name,
                'description' => $new_app_desc
            ]);
            return redirect('/workspace/applications');
        }
        return view('Applications.app_create');
    }

    public function delete(Request $request)
    {
        $app_id = $request->input('applicationID');
        $current_page = $request->input('page');
        $application = Applications::find($app_id);
        $application->delete();
        return redirect('/workspace/applications?page=' . $current_page);
    }

    public function update(Request $request)
    {
        $app_id = $request->input('applicationID');
        $application = Applications::find($app_id);
        $current_page = $request->input('page');
        if ($request->isMethod('post')) {
            $input = $request->all();
            //Applications validate
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('applications')->ignore($input['applicationID'])
                ],
                'description' => [
                    'required',
                    'max:250',
                    Rule::unique('applications')->ignore($input['applicationID'])
                ]
            ])->validate();
            $app_name = $request->input('name');
            $app_desc = $request->input('description');
            $application['name'] = $app_name;
            $application['description'] = $app_desc;
            $application->save();
            return redirect('/workspace/applications?page=' . $current_page);
        }
        return view('Applications.app_update', ['application' => $application]);
    }
}
