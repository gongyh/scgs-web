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
            // 数据库中没有species显示
            $all_species = null;
            $current_page = null;
            return view('Species.species', ['all_species' => $all_species, 'current_page' => $current_page]);
        }
    }

    public function create(Request $request)
    {
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        if ($request->isMethod('POST')) {
            // species create validate
            $this->validate($request, [
                'new_species_name' => 'required|unique:species,name|max:250',
                'new_fasta' => ['required', 'regex:{\.(fasta|fa)$}'],
                'new_gff' => ['required', 'regex:{\.gff$}']
            ]);
            $new_species_name = $request->input('new_species_name');
            $new_fasta = $request->input('new_fasta');
            $new_gff = $request->input('new_gff');
            // 验证fasta、gff文件是否存在，如不存在返回错误信息

            // 判断输入是否是绝对路径
            if (strpos($new_fasta, $base_path) == 0) {
                // 相对路径
                $new_fasta_path = str_replace($base_path, '', $new_fasta);
                $fasta_exist = Storage::disk('local')->exists($new_fasta_path);
            } else {
                $fasta_exist = Storage::disk('local')->exists($new_fasta);
            }
            if (strpos($new_gff, $base_path) == 0) {
                $new_gff_path = str_replace($base_path, '', $new_gff);
                $gff_exist = Storage::disk('local')->exists($new_gff_path);
            } else {
                $gff_exist = Storage::disk('local')->exists($new_gff);
            }

            //  统一保存为相对路径
            $new_fasta = $new_fasta_path ? $new_fasta_path : $new_fasta;
            $new_gff = $new_gff_path ? $new_gff_path : $new_gff;

            //  判断是否存在反斜杠\
            $new_fasta = strpos($new_fasta, '\\') !== false ? str_replace('\\', '/', $new_fasta) : $new_fasta;
            $new_gff = strpos($new_gff, '\\') !== false ? str_replace('\\', '/', $new_gff) : $new_gff;

            if (!$fasta_exist && $gff_exist) {
                $file_error = 'fasta file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error, 'base_path' => $base_path]);
            } elseif ($fasta_exist && !$gff_exist) {
                $file_error = 'gff file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error, 'base_path' => $base_path]);
            } elseif (!$fasta_exist && !$gff_exist) {
                $file_error = 'fasta file and gff file doesn\'t exist';
                return view('Species.species_create', ['file_error' => $file_error, 'base_path' => $base_path]);
            } else {
                Species::create([
                    'name' => $new_species_name,
                    'fasta' => $new_fasta,
                    'gff' => $new_gff
                ]);
                return redirect('/workspace/species');
            }
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
            // species update validate
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
            // 验证fasta、gff文件是否存在，如不存在返回错误信息
            //  判断输入的是否是绝对路径
            if (strpos($fasta, $base_path) == 0) {
                //  相对路径
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

            //  统一保存为相对路径
            $fasta = $fasta_path ? $fasta_path : $fasta;
            $gff = $gff_path ? $gff_path : $gff;

            //  判断是否存在反斜杠\
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
