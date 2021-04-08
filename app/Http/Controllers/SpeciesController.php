<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Species;
use Illuminate\Http\Request;
use App\Imports\SpeciesImport;
use Maatwebsite\Excel\Facades\Excel;
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
            // If there are no species in database
            $all_species = null;
            $current_page = null;
            return view('Species.species', ['all_species' => $all_species, 'current_page' => $current_page]);
        }
    }

    public function create(Request $request)
    {
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            // Species create validate
            $this->validate($request, [
                'new_species_name' => 'required|unique:species,name|max:250',
                'new_reference_genome' => ['required'],
                'new_genome_annotation' => ['required']
            ]);
            $new_species_name = $request->input('new_species_name');
            if($request->file('new_reference_genome')->isValid()){
                $reference_name = $request->file('new_reference_genome')->getClientOriginalName();
                $reference_path = $request->file('new_reference_genome')->storeAs(env('','reference_genome'),$reference_name);

            }
            if($request->file('new_genome_annotation')->isValid()){
                $annotation_name = $request->file('new_genome_annotation')->getClientOriginalName();
                $annotation_path = $request->file('new_genome_annotation')->storeAs(env('','annotation_genome'),$annotation_name);
            }
            Species::create([
                'name' => $new_species_name,
                'fasta' => $reference_path,
                'gff' => $annotation_path
            ]);
            return redirect('/workspace/species');
        }
        return view('Species.species_create', ['base_path' => $base_path]);
    }

    public function delete(Request $request)
    {
        $species_id = $request->input('speciesID');
        $current_page = $request->input('page');
        $species = Species::find($species_id);
        $species->delete();
        return redirect('/workspace/species?page=' . $current_page);
    }

    public function update(Request $request)
    {
        $species_id = $request->input('speciesID');
        $species = Species::find($species_id);
        $current_page = $request->input('page');
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('post')) {
            $input = $request->all();
            // Species update validate
            Validator::make($input, [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('species')->ignore($input['speciesID'])
                ],
                'fasta' => [
                    'required',
                    'regex:{\.(fasta|fa)$}',
                    Rule::unique('species')->ignore($input['speciesID'])
                ],
                'gff' => [
                    'required',
                    'regex:{\.gff$}',
                    Rule::unique('species')->ignore($input['speciesID'])
                ]
            ])->validate();
            $species_name = $request->input('name');
            $fasta = $request->input('fasta');
            $gff = $request->input('gff');
            // To validate fasta,gff files existed

            // To validate absolute path
            if (strpos($fasta, $base_path) == 0) {
                // Relative path
                $fasta_path = str_replace($base_path, '', $fasta);
                $fasta_exist = Storage::disk('local')->exists($fasta_path);
            } else {
                $fasta_exist = Storage::disk('local')->exists($fasta);
            }
            if (strpos($gff, $base_path) == 0) {
                $gff_path = str_replace($base_path, '', $gff);
                $gff_exist = Storage::disk('local')->exists($gff_path);
            } else {
                $gff_exist = Storage::disk('local')->exists($gff);
            }

            // Save as relative path
            $fasta = $fasta_path ? $fasta_path : $fasta;
            $gff = $gff_path ? $gff_path : $gff;

            $fasta = strpos($fasta, '\\') !== false ? str_replace('\\', '/', $fasta) : $fasta;
            $gff = strpos($gff, '\\') !== false ? str_replace('\\', '/', $gff) : $gff;

            if (!$fasta_exist && $gff_exist) {
                $file_error = 'fasta file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error, 'base_path' => $base_path]);
            } elseif ($fasta_exist && !$gff_exist) {
                $file_error = 'gff file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error, 'base_path' => $base_path]);
            } elseif (!$fasta_exist && !$gff_exist) {
                $file_error = 'fasta file and gff file doesn\'t exist';
                return view('Species.species_update', ['species' => $species, 'file_error' => $file_error, 'base_path' => $base_path]);
            } else {
                $species['name'] = $species_name;
                $species['fasta'] = $fasta;
                $species['gff'] = $gff;
                $species->save();
                return redirect('/workspace/species?page=' . $current_page);
            }
        }
        return view('Species.species_update', ['species' => $species, 'base_path' => $base_path]);
    }

    public function upload(Request $request)
    {
        if ($request->file('species_file')->isValid()) {
            $filename = 'Species_temp.xlsx';
            $request->file('species_file')->storeAs('', $filename);
            Excel::import(new SpeciesImport, $filename);
            Storage::delete($filename);
            return response()->json(['code' => '200']);
        }
    }

    public function download()
    {
        return response()->download(storage_path('Species_template.xlsx'));
    }
}
