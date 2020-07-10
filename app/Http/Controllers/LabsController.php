<?php

namespace App\Http\Controllers;

use App\Labs;
use Illuminate\Http\Request;

class LabsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $labs = Labs::paginate(15);
        return view('labs', ['labs'=>$labs]);
    }
}
