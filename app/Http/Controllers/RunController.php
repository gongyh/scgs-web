<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Status;
use App\Samples;
use App\Execparams;

class RunController extends Controller
{
    //
    public function index()
    {
        $samples = new Samples();
        $status = new Status();
        $execparams = new Execparams();
        $user_id = Auth::user()->id;
        $samples_user = Status::where([['user_id', $user_id], ['status', false]])->get();
        $now = time();
        return view('workspace.runningSample', ['samples_user' => $samples_user, 'samples' => $samples, 'status' => $status, 'now' => $now, 'execparams' => $execparams]);
    }
}
