<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RamanResultController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('Raman.Raman');
    }
}
