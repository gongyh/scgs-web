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

    public function update(Request $request,$id)
    {
        $institution = Institutions::find($id);
        if($request->isMethod('post')){
            $new_instiname = $request->input('new-stiname');
            $institution['name'] =$new_instiname;

            if($institution->save()){
                return redirect('/institutions');
            }
        }
        return view('insti_update',['institution'=>$institution]);
    }

    public function delete($id){
        $institution = Institutions::find($id);
        $institution->delete();
        return redirect('/institutions');
    }
}
