<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Execparams;
use App\pipelineParams;
use App\Samples;
use App\Species;
use App\Jobs;
use App\Jobs\RunPipeline;

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            /**
             * 接收表单post的数据
             */
            $samples = new Samples();
            $pipelineParams = pipelineParams::find(1);
            $sample_id = $request->input('sampleID');
            $ass = $request->input('ass') == 'ass' ? true : false;
            $snv = $request->input('snv') == 'snv' ? true : false;
            $cnv = $request->input('cnv') == 'cnv' ? true : false;
            $bulk = $request->input('bulk') == 'bulk' ? true : false;
            $saturation = $request->input('saturation') == 'saturation' ? true : false;
            $acquired = $request->input('acquired') == 'acquired' ? true : false;
            $saveTrimmed = $request->input('saveTrimmed') == 'saveTrimmed' ? true : false;
            $saveAlignedIntermediates = $request->input('saveAlignedIntermediates') == 'saveAlignedIntermediates' ? true : false;
            $euk = $request->input('euk') == 'euk' ? true : false;
            $fungus = $request->input('fungus') == 'fungus' ? true : false;
            $resume = $request->input('resume') == 'resume' ? true : false;
            $genus = $request->input('genus') == 'genus' ? true : false;
            $busco_seed_species = $request->input('busco_seed_species') == 'busco_seed_species' ? true : false;
            $resfinder_db = $request->input('resfinder_db') == 'resfinder_db' ? true : false;
            $nt_db = $request->input('nt_db') == 'nt_db' ? true : false;
            $eggnog = $request->input('eggnog_db') == 'eggnog_db' ? true : false;
            $kraken_db = $request->input('kraken_db') == 'kraken_db' ? true : false;
            $kofam_profile = $request->input('kofam_profile') == 'kofam_profile' ? true : false;
            $kofam_kolist = $request->input('kofam_kolist') == 'kofam_kolist' ? true : false;
            $funannotate_db = $request->input('funannotate_db') == 'funannotate_db' ? true : false;
            if ($request->input('genus') != null) {
                $this->validate($request, [
                    'genus_name' => 'required|max:200'
                ]);
                $genus_name = $request->input('genus_name');
            } else {
                $genus_name = null;
            }
            if ($request->input('busco_seed_species') != null) {
                $busco_seed_species_name = $request->input('busco_seed_species_name');
            } else {
                $busco_seed_species_name = null;
            }
            /**
             * 判断execparams表中是否有该sample运行的参数，如果没有就添加记录，如果有就修改记录
             */
            if (Execparams::where('samples_id', $sample_id)->get()->count() == 0) {
                Execparams::create([
                    'samples_id' => $sample_id,
                    'ass' => $ass,
                    'snv' => $snv,
                    'cnv' => $cnv,
                    'bulk' => $bulk,
                    'saturation' => $saturation,
                    'acquired' => $acquired,
                    'saveTrimmed' => $saveTrimmed,
                    'saveAlignedIntermediates' => $saveAlignedIntermediates,
                    'euk' => $euk,
                    'fungus' => $fungus,
                    'resume' => $resume,
                    'genus' => $genus,
                    'genus_name' => $genus_name,
                    'busco_seed_species' => $busco_seed_species,
                    'busco_seed_species_name' => $busco_seed_species_name,
                    'resfinder_db' => $resfinder_db,
                    'nt_db' => $nt_db,
                    'eggnog' => $eggnog,
                    'kraken_db' => $kraken_db,
                    'kofam_profile' => $kofam_profile,
                    'kofam_kolist' => $kofam_kolist,
                    'funannotate_db' => $funannotate_db
                ]);
            } else {
                $id = Execparams::where('samples_id', $sample_id)->value('id');
                $execparams = Execparams::find($id);
                $execparams->ass = $ass;
                $execparams->snv = $snv;
                $execparams->cnv = $cnv;
                $execparams->bulk = $bulk;
                $execparams->saturation = $saturation;
                $execparams->acquired = $acquired;
                $execparams->saveTrimmed = $saveTrimmed;
                $execparams->saveAlignedIntermediates = $saveAlignedIntermediates;
                $execparams->euk = $euk;
                $execparams->fungus = $fungus;
                $execparams->resume = $resume;
                $execparams->genus = $genus;
                $execparams->genus_name = $genus_name;
                $execparams->busco_seed_species = $busco_seed_species;
                $execparams->busco_seed_species_name = $busco_seed_species_name;
                $execparams->resfinder_db = $resfinder_db;
                $execparams->kraken_db = $kraken_db;
                $execparams->nt_db = $nt_db;
                $execparams->eggnog = $eggnog;
                $execparams->kofam_profile = $kofam_profile;
                $execparams->kofam_kolist = $kofam_kolist;
                $execparams->funannotate_db = $funannotate_db;
                $execparams->save();
            }

            /**
             * execparams参数表读取，拼接command
             */
            $execparams = new Execparams();
            $run_sample = $execparams->where('samples_id', $sample_id);
            $ass = $run_sample->value('ass') ? '--ass ' : '';
            $cnv = $run_sample->value('cnv') ? '--cnv ' : '';
            $snv = $run_sample->value('snv') ? '--snv ' : '';
            $bulk = $run_sample->value('bulk') ? '--bulk ' : '';
            $saturation = $run_sample->value('saturation') ? '--saturation ' : '';
            $acquired = $run_sample->value('acquired') ? '--acquired ' : '';
            $saveTrimmed = $run_sample->value('saveTrimmed') ? '--saveTrimmed ' : '';
            $saveAlignedIntermediates = $run_sample->value('saveAlignedIntermediates') ? '--saveAlignedIntermediates ' : '';
            $euk = $run_sample->value('euk') ? '--euk ' : '';
            $fungus = $run_sample->value('fungus') ? '--fungus ' : '';
            $resume = $run_sample->value('resume') ? '-resume ' : '';
            if ($run_sample->value('genus')) {
                $genus_name = $run_sample->value('genus_name');
                $genus = '--genus ' . $genus_name . ' ';
            } else {
                $genus = '';
            }
            if ($run_sample->value('busco_seed_species')) {
                $busco_seed_species_name = $run_sample->value('busco_seed_species_name');
                $busco_seed_species = '--busco_seed_species ' . $busco_seed_species_name . ' ';
            } else {
                $busco_seed_species = '';
            }
            /**
             * pipeline params读取数据库路径
             */
            $pipeline_params = pipelineParams::find(1);
            $resfinder_db_path = $pipeline_params->resfinder_db_path;
            $nt_db_path = $pipeline_params->nt_db_path;
            $eggnog_db_path = $pipeline_params->eggnog_db_path;
            $kraken_db_path = $pipeline_params->kraken_db_path;
            $kofam_profile_path = $pipeline_params->kofam_profile_path;
            $kofam_kolist_path = $pipeline_params->kofam_kolist_path;
            $funannotate_db_path = $pipeline_params->funannotate_db_path;
            $resfinder_db = $run_sample->value('resfinder_db') ? '--resfinder_db ' . $resfinder_db_path . ' ' : '';
            $nt_db = $run_sample->value('nt_db') ? '--nt_db ' . $nt_db_path . ' ' : '';
            $kraken_db = $run_sample->value('kraken_db') ? '--kraken_db ' . $kraken_db_path . ' ' : '';
            $eggnog_db = $run_sample->value('eggnog') ? '--eggnog_db ' . $eggnog_db_path . ' ' : '';
            $kofam_profile = $run_sample->value('kofam_profile') ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
            $kofam_kolist = $run_sample->value('kofam_kolist') ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';
            $funannotate_db = $run_sample->value('funannotate_db') ? '--funannotate_db ' . $funannotate_db_path . ' ' : '';

            $species_id = Samples::where('id', $sample_id)->value('species_id');
            $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
            if (isset($species_id)) {
                $fasta_path = Species::where('id', $species_id)->value('fasta');
                $gff_path = Species::where('id', $species_id)->value('gff');
                $fasta_path = $base_path . '' . $fasta_path;
                $gff_path = $base_path . '' . $gff_path;
                $fasta = '--fasta ' . $fasta_path . ' ';
                $gff = '--gff ' . $gff_path . ' ';
            } else {
                $fasta = $gff = '';
            }

            $sample = Samples::find($sample_id);
            $filename1 = $sample->filename1;
            $filename1 = $base_path . '' . $filename1;

            $sample->pairends ? $filename2 = $sample->filename2 : $filename2 = null;
            if (strpos($filename1, '_R1')) {
                $filename = str_replace('_R1', '_R[1,2]', $filename1);
            } elseif (strpos($filename1, '_1')) {
                $filename = str_replace('_1', '_[1,2]', $filename1);
            }

            //保存目录格式 : 用户名 + 物种名(sampleLabel)
            $sample_label = Samples::where('id', $sample_id)->value('sampleLabel');
            $sample_user_name = Auth::user()->name;

            if ($filename2 != null) {
                //pairEnds
                $cmd = '/opt/images/bin/nextflow run opt/images/nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $busco_seed_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $funannotate_db . '-profile docker,base ' . $resume . '--outdir results -w work';
            } else {
                //singleEnds
                $cmd = '/opt/images/bin/nextflow run opt/images/nf-core-scgs ' . '--reads ' . '"' . $filename1 . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $busco_seed_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $funannotate_db . '--singleEnds -profile docker,base ' . $resume . '--outdir results -w work';
            }

            /**
             * jobs表中添加记录
             */
            $user_id = Auth::user()->id;
            $sample_id = $request->input('sampleID');
            if (Jobs::where('sample_id', $sample_id)->count() == 0) {
                Jobs::create([
                    'user_id' => $user_id,
                    'sample_id' => $sample_id,
                    'uuid' => 'default',
                    'started' => '000',
                    'finished' => '000',
                    'command' => $cmd,
                    'status' => 0   // 0:未开始
                ]);
            } else {
                $job_id = Jobs::where('sample_id', $sample_id)->value('id');
                $job = Jobs::find($job_id);
                $job->user_id = $user_id;
                $job->sample_id = $sample_id;
                $job->started = '000';
                $job->finished = '000';
                $job->command = $cmd;
                $job->status = 0;
                $job->save();
            }
            RunPipeline::dispatch($sample_id);
            return redirect('/execute/start?sampleID=' . $sample_id);
        }
        $pipelineParams = pipelineParams::find(1);
        $sample_id = $request->input('sampleID');
        $can_exec = Jobs::where('sample_id', $sample_id)->count() == 0 || Jobs::where('sample_id', $sample_id)->orderBy('id', 'desc')->value('status') == 2 || Jobs::where('sample_id', $sample_id)->orderBy('id', 'desc')->value('status') == 3;
        if (Execparams::where('samples_id', $sample_id)->get()->count() != 0) {
            $data = Execparams::where('samples_id', $sample_id);
            $ass = $data->value('ass');    //boolean
            $cnv = $data->value('cnv');    //boolean
            $snv = $data->value('snv');    //boolean
            $bulk = $data->value('bulk');    //boolean
            $saturation = $data->value('saturation');    //boolean
            $acquired = $data->value('acquired');    //boolean
            $saveTrimmed = $data->value('saveTrimmed');    //boolean
            $saveAlignedIntermediates = $data->value('saveAlignedIntermediates');    //boolean
            $euk = $data->value('euk');    //boolean
            $fungus = $data->value('fungus');    //boolean
            $resume = $data->value('resume');    //boolean
            $genus = $data->value('genus');     //boolean
            $genus_name = $data->value('genus_name');    //string
            $busco_seed_species = $data->value('busco_seed_species');    //boolean
            $busco_seed_species_name = $data->value('busco_seed_species_name');    //string
            $resfinder_db = $data->value('resfinder_db');     //boolean
            $nt_db = $data->value('nt_db');     //boolean
            $kraken_db = $data->value('kraken_db');     //boolean
            $eggnog = $data->value('eggnog');    //boolean
            $kofam_profile = $data->value('kofam_profile');    //boolean
            $kofam_kolist = $data->value('kofam_kolist');     //boolean
            $funannotate_db = $data->value('funannotate_db');     //boolean
            $busco_seed_species_lists = array(
                'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
            );
            return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'busco_seed_species', 'busco_seed_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'funannotate_db', 'sample_id', 'pipelineParams', 'can_exec', 'busco_seed_species_lists'));
        } else {
            $ass = $cnv = $snv = $bulk = $saturation = $acquired = $saveTrimmed = $saveAlignedIntermediates = $resume = $euk = $fungus = $genus = $busco_seed_species = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = $funannotate_db = false;
            $genus_name = $busco_seed_species_name = null;
            $busco_seed_species_lists = array(
                'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
            );
            return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'busco_seed_species', 'busco_seed_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'funannotate_db', 'sample_id', 'pipelineParams', 'can_exec', 'busco_seed_species_lists'));
        }
    }

    public function start(Request $request)
    {
        $pipelineParams = pipelineParams::find(1);
        $samples = new Samples();
        $sample_id = $request->input('sampleID');
        $data = Execparams::where('samples_id', $sample_id);
        $ass = $data->value('ass');    //boolean
        $cnv = $data->value('cnv');    //boolean
        $snv = $data->value('snv');    //boolean
        $bulk = $data->value('bulk');    //boolean
        $saturation = $data->value('saturation');    //boolean
        $acquired = $data->value('acquired');    //boolean
        $saveTrimmed = $data->value('saveTrimmed');    //boolean
        $saveAlignedIntermediates = $data->value('saveAlignedIntermediates');    //boolean
        $euk = $data->value('euk');    //boolean
        $fungus = $data->value('fungus');    //boolean
        $resume = $data->value('resume');    //boolean
        $genus = $data->value('genus');     //boolean
        $genus_name = $data->value('genus_name');    //string
        $busco_seed_species = $data->value('busco_seed_species');    //string
        $busco_seed_species_name = $data->value('busco_seed_species_name');    //string
        $resfinder_db = $data->value('resfinder_db');     //boolean
        $nt_db = $data->value('nt_db');     //boolean
        $kraken_db = $data->value('kraken_db');     //boolean
        $eggnog = $data->value('eggnog');    //boolean
        $kofam_profile = $data->value('kofam_profile');    //boolean
        $kofam_kolist = $data->value('kofam_kolist');     //boolean
        $funannotate_db = $data->value('funannotate_db');     //boolean
        return view('Pipeline.pipelineStart', compact('samples', 'ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'busco_seed_species', 'busco_seed_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'funannotate_db', 'sample_id', 'pipelineParams'));
    }

    public function ajax(Request $request)
    {
        $running_sample_id = $request->input('running_sample_id');
        $uuid = Jobs::where([['sample_id', '=', $running_sample_id], ['status', '=', 1]])->value('uuid');
        $user_id = Jobs::where('sample_id', $running_sample_id)->value('user_id');
        $run_sample_user = User::where('id', $user_id)->value('name');
        $nextflow_log_path = $run_sample_user . '/' . $uuid . '/.nextflow.log';
        if (Storage::disk('local')->exists($nextflow_log_path)) {
            $data = Storage::get($nextflow_log_path);
            return response()->json(['code' => '200', 'data' => $data]);
        } else {
            return response()->json(['code' => '201', 'data' => '']);
        }
    }
}
