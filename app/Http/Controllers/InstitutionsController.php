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
        $institutions = Institutions::find($id);
        if($request->isMethod('post')){
            $new_instiname = $request->input('new-stiname');
            $institutions['name'] =$new_instiname;

            if($institutions->save()){
                return redirect('/institutions');
            }
        }
        return view('insti_update');
    }

    public function delete($id){
        $institutions = Institutions::find($id);
        if($institutions->delete()){
            return redirect('/institutions');
        }
    }
}
