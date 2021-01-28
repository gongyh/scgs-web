<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($page_id = "create_project")
    {
        $nav = file_get_contents(resource_path('views/docs/navs.md'));
        $content = file_get_contents(resource_path('views/docs/'.$page_id.'.md'));
        return view('docs/index',['nav'=>$nav, 'md'=>$content]);
    }

}
