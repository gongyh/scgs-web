<?php

namespace App\Http\Controllers;

use App\Labs;
use Illuminate\Http\Request;

class LabsPasswdController extends Controller
{
    public function passwdCheck(Request $request)
    {
        $selectlabID = $request->input('labID');
        if ($request->isMethod("POST")) {
            $labLogin = $request->input('labLogin');
            $labPw = $request->input('labPw');
            if (Labs::where('login', $labLogin)->get()->count() > 0 && Labs::where('password', $labPw)->get()->count() > 0) {
                return redirect('institutions/labs/projects?labID=' . $selectlabID);
            } else {
                $error = 'Sorry!Your Login Name or Password is Wrong!';
                return view('labPasswd', ['error' => $error]);
            }
        } else {
            return view('labPasswd');
        }
    }
}
