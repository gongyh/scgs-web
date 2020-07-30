<?php

namespace App\Http\Controllers;

use App\Labs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MineController extends Controller
{
    //
    public function myLab()
    {
        $user = Auth::user();
        try {
            $myLabs = Labs::where('principleInvestigator', $user->name)->paginate(15);
            return view('Mine.myLab', ['myLabs' => $myLabs]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $myLabs = null;
            return view('Mine.myLab', ['myLabs' => $myLabs]);
        }
    }
}
