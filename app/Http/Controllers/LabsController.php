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

    public function update(Request $request,$id)
    {
        $lab = Labs::find($id);
        if($request->isMethod('post')){
            $new_labname = $request->input('new-labname');
            $lab['name'] =$new_labname;

            if($lab->save()){
                return redirect('/labs');
            }
        }
        return view('labs_update',['lab'=>$lab]);
    }

    public function delete($id){
        $lab = Labs::find($id);
        if($lab->delete()){
            return redirect('/labs');
        }
    }
}
