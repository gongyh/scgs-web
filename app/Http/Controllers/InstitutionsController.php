<?php

namespace App\Http\Controllers;

use App\Institutions;
use Illuminate\Http\Request;

class InstitutionsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $institutions = Institutions::paginate(15);
        return view('institutions', ['institutions'=>$institutions]);
    }
}
