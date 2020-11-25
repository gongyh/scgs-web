<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Jobs;
use App\Samples;
use App\Projects;
use App\Execparams;

class RunController extends Controller
{
    //
    public function index()
    {
        $samples = new Samples();
        $projects = new Projects();
        $jobs = new Jobs();
        $execparams = new Execparams();
        $user_id = Auth::user()->id;
        $now = time();
        $user_jobs = Jobs::where('user_id', $user_id)->get();
        return view('Workspace.pipelineStatus', ['user_jobs' => $user_jobs, 'projects' => $projects, 'samples' => $samples, 'jobs' => $jobs, 'now' => $now, 'execparams' => $execparams]);
    }
}
