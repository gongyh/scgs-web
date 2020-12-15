<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spectra;
use App\Projects;

class RamanResultController extends Controller
{
    //
    public function index(Request $request)
    {
        $projectID = $request->input('projectID');
        $project = Projects::find($projectID);
        $spectra = Spectra::where('project', $project->doi)->where('dtype', 'raw')->paginate(50);
        return view('Raman.Raman', compact('spectra', 'projectID'));
    }
}
