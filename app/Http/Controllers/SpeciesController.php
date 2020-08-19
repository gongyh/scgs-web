<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeciesController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $all_species = Species::paginate(15);
            $current_page = $request->input('page');
            return view('Species.species', ['all_species' => $all_species, 'current_page' => $current_page]);
        } catch (\Illuminate\Database\QueryException $ex) {
            // 数据库中没有species显示
            $all_species = null;
            $current_page = null;
            return view('Species.species', ['all_species' => $all_species, 'current_page' => $current_page]);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            // species create validate
            $this->validate($request, [
                'new_species_name' => 'required|unique:species,name|max:250',
                'new_fasta' => ['required', 'regex:{((\w)+\/)*(\w)+\.fasta$}'],
                'new_gff' => ['required', 'regex:{((\w)+\/)*(\w)+\.gff$}']
            ]);
            $new_species_name = $request->input('new_species_name');
            $new_fasta = $request->input('new_fasta');
            $new_gff = $request->input('new_gff');
            // 验证fasta、gff文件是否存在，如不存在返回错误信息
            $fasta_exist = Storage::disk('local')->exists($new_fasta);
            $gff_exist = Storage::disk('local')->exists($new_gff);
            if (!$fasta_exist && $gff_exist) {
                $file_error = 'fasta file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error]);
            } elseif ($fasta_exist && !$gff_exist) {
                $file_error = 'gff file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error]);
            } elseif (!$fasta_exist && !$gff_exist) {
                $file_error = 'fasta file and gff file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error]);
            } else {
                Species::create([
                    'name' => $new_species_name,
                    'fasta' => $new_fasta,
                    'gff' => $new_gff
                ]);
                return redirect('/species');
            }
        }
        return view('Species.species_create');
    }

    public function delete(Request $request)
    {
        $species_id = $request->input('speciesID');
        $current_page = $request->input('page');
        $species = Species::find($species_id);
        $species->delete();
        return redirect('/species?page=' . $current_page);
    }

    public function update(Request $request)
    {
        $species_id = $request->input('speciesID');
        $species = Species::find($species_id);
        $current_page = $request->input('page');
        if ($request->isMethod('post')) {
            $input = $request->all();
            // species update validate
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('species')->ignore($input['speciesID'])
                ],
                'fasta' => [
                    'required',
                    'regex:{((\w)+\/)*(\w)+\.fasta$}',
                    Rule::unique('species')->ignore($input['speciesID'])

                ],
                'gff' => [
                    'required',
                    'regex:{((\w)+\/)*(\w)+\.gff$}',
                    Rule::unique('species')->ignore($input['speciesID'])
                ]
            ])->validate();
            $species_name = $request->input('name');
            $fasta = $request->input('fasta');
            $gff = $request->input('gff');
            // 验证fasta、gff文件是否存在，如不存在返回错误信息
            $fasta_exist = Storage::disk('local')->exists($fasta);
            $gff_exist = Storage::disk('local')->exists($gff);
            if (!$fasta_exist && $gff_exist) {
                $file_error = 'fasta file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error]);
            } elseif ($fasta_exist && !$gff_exist) {
                $file_error = 'gff file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error]);
            } elseif (!$fasta_exist && !$gff_exist) {
                $file_error = 'fasta file and gff file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error]);
            } else {
                $species['name'] = $species_name;
                $species['fasta'] = $fasta;
                $species['gff'] = $gff;
                $species->save();
                return redirect('/species?page=' . $current_page);
            }
        }
        return view('Species.species_update', ['species' => $species]);
    }
}
