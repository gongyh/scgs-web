<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Execparams;

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        $sample_id = $request->input('sampleID');
        if ($request->method() == 'POST') {
            $ass = $request->input('ass') == 'ass' ? true : false;
            $snv = $request->input('snv') == 'snv' ? true : false;
            $cnv = $request->input('cnv') == 'cnv' ? true : false;
            $genus = $request->input('genus') == 'genus' ? true : false;
            $resfinder_db = $request->input('resfinder_db') == 'resfinder_db' ? true : false;
            $nt_db = $request->input('nt_db') == 'nt_db' ? true : false;
            $eggnog = $request->input('eggnog_db') == 'eggnog_db' ? true : false;
            $kraken_db = $request->input('kraken_db') == 'kraken_db' ? true : false;
            $kofam_profile = $request->input('kofam_profile') == 'kofam_profile' ? true : false;
            $kofam_kolist = $request->input('kofam_kolist') == 'kofam_kolist' ? true : false;
            if ($request->input('genus') != null) {
                $this->validate($request, [
                    'genus_name' => 'required|max:200'
                ]);
                $genus_name = $request->input('genus_name');
            } else {
                $genus_name = null;
            }
            if (Execparams::where('samples_id', $sample_id)->get()->count() == 0) {
                Execparams::create([
                    'samples_id' => $sample_id,
                    'ass' => $ass,
                    'snv' => $snv,
                    'cnv' => $cnv,
                    'genus' => $genus,
                    'genus_name' => $genus_name,
                    'resfinder_db' => $resfinder_db,
                    'nt_db' => $nt_db,
                    'eggnog' => $eggnog,
                    'kraken_db' => $kraken_db,
                    'kofam_profile' => $kofam_profile,
                    'kofam_kolist' => $kofam_kolist
                ]);
            } else {
                $id = Execparams::where('samples_id', $sample_id)->value('id');
                $execparams = Execparams::find(1);
                $execparams['ass'] = $ass;
                $execparams['snv'] = $snv;
                $execparams['cnv'] = $cnv;
                $execparams['genus'] = $genus;
                $execparams['genus_name'] = $genus_name;
                $execparams['resfinder_db'] = $resfinder_db;
                $execparams['nt_db'] = $nt_db;
                $execparams['eggnog'] = $eggnog;
                $execparams['kofam_profile'] = $kofam_profile;
                $execparams['kofam_kolist'] = $kofam_kolist;
                $execparams->save();
            }
            return view('Workspace.execParams', compact('ass', 'cnv', 'snv', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist'));
        } else {
            if (Execparams::where('samples_id', $sample_id)->get()->count() != 0) {
                $data = Execparams::where('samples_id', $sample_id);
                $ass = $data->value('ass');    //boolean
                $cnv = $data->value('cnv');    //boolean
                $snv = $data->value('snv');    //boolean
                $genus = $data->value('genus');     //boolean
                $genus_name = $data->value('genus_name');    //string
                $resfinder_db = $data->value('resfinder_db');     //boolean
                $nt_db = $data->value('nt_db');     //boolean
                $kraken_db = $data->value('kraken_db');     //boolean
                $eggnog = $data->value('eggnog');    //boolean
                $kofam_profile = $data->value('kofam_profile');    //boolean
                $kofam_kolist = $data->value('kofam_kolist');     //boolean
                return view('Workspace.execParams', compact('ass', 'cnv', 'snv', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist'));
            } else {
                $ass = $cnv = $snv = $genus = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = false;
                $genus_name = null;
                return view('Workspace.execParams', compact('ass', 'cnv', 'snv', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist'));
            }
        }
    }
}
