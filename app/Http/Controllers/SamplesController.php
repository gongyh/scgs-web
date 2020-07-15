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

    public function update(Request $request,$id)
    {
        $sample = Samples::find($id);
        if($request->isMethod('post')){
            $new_sampname = $request->input('new-sampname');
            $sample['name'] =$new_sampname;

            if($sample->save()){
                return redirect('/samples');
            }
        }
        return view('samp_update',['sample'=>$sample]);
    }

    public function delete($id){
        $sample = Samples::find($id);
        if($sample->delete()){
            return redirect('/samples');
        }
    }
}
