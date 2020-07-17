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
        try{
            $lab = Labs::findOrFail($id);
            if($request->isMethod('POST')){
                $new_labname = $request->input('new-labname');
                $lab['name'] =$new_labname;
                $lab->save();
                return redirect('/labs');
            }
            return view('labs_update',['lab'=>$lab]);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            return 'sorry!can not update!';
        }
    }

    public function delete($id){
        $lab = Labs::find($id);
        $lab->delete();
        return redirect('/labs');
    }
}
