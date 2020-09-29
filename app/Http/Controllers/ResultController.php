<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ResultController extends Controller
{
    //
    public function index(Request $request)
    {
        $uuid = $request->input('uuid');
        $user_id = Jobs::where('uuid', $uuid)->value('user_id');
        $user_name = User::where('id', $user_id)->value('name');
        $file_path = $user_name . '/' . $uuid . '/.nextflow.log';
        $nextflowLog = Storage::get('.nextflow.log');
        return view('RunResult.failedRunning', ['nextflowLog' => $nextflowLog]);
    }
}
