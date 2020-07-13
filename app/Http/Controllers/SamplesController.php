<?php

namespace App\Http\Controllers;

use App\Samples;
use Illuminate\Http\Request;

class SamplesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $samples = Samples::paginate(15);
        return view('samples', ['samples'=>$samples]);
    }
}
