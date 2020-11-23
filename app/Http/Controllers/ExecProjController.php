<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use App\Execparams;
use App\pipelineParams;
use App\Projects;
use App\Labs;
use App\Samples;
use App\Species;
use App\Jobs;
use App\Jobs\RunPipeline;
use App\Jobs\RunProjPipeline;

class ExecProjController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            /**
             * form post data
             */
            $pipelineParams = pipelineParams::find(1);
            $project_id = $request->input('projectID');
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
            $augustus_species = $request->input('augustus_species') == 'augustus_species' ? true : false;
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
            if ($request->input('augustus_species') != null) {
                $augustus_species_name = $request->input('augustus_species_name');
            } else {
                $augustus_species_name = null;
            }
            /**
             * 判断execparams表中是否有该sample运行的参数，如果没有就添加记录，如果有就修改记录
             */
            if (Execparams::where('project_id', $project_id)->get()->count() == 0) {
                Execparams::create([
                    'sample_id' => null,
                    'project_id' => $project_id,
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
                    'augustus_species' => $augustus_species,
                    'augustus_species_name' => $augustus_species_name,
                    'resfinder_db' => $resfinder_db,
                    'nt_db' => $nt_db,
                    'eggnog' => $eggnog,
                    'kraken_db' => $kraken_db,
                    'kofam_profile' => $kofam_profile,
                    'kofam_kolist' => $kofam_kolist,
                ]);
            } else {
                $id = Execparams::where('project_id', $project_id)->value('id');
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
                $execparams->augustus_species = $augustus_species;
                $execparams->augustus_species_name = $augustus_species_name;
                $execparams->resfinder_db = $resfinder_db;
                $execparams->kraken_db = $kraken_db;
                $execparams->nt_db = $nt_db;
                $execparams->eggnog = $eggnog;
                $execparams->kofam_profile = $kofam_profile;
                $execparams->kofam_kolist = $kofam_kolist;
                $execparams->save();
            }

            /**
             * execparams参数表读取，拼接command
             */
            $execparams = new Execparams();
            $run_project = $execparams->where('project_id', $project_id);
            $ass = $run_project->value('ass') ? '--ass ' : '';
            $cnv = $run_project->value('cnv') ? '--cnv ' : '';
            $snv = $run_project->value('snv') ? '--snv ' : '';
            $bulk = $run_project->value('bulk') ? '--bulk ' : '';
            $saturation = $run_project->value('saturation') ? '--saturation ' : '';
            $acquired = $run_project->value('acquired') ? '--acquired ' : '';
            $saveTrimmed = $run_project->value('saveTrimmed') ? '--saveTrimmed ' : '';
            $saveAlignedIntermediates = $run_project->value('saveAlignedIntermediates') ? '--saveAlignedIntermediates ' : '';
            $euk = $run_project->value('euk') ? '--euk ' : '';
            $fungus = $run_project->value('fungus') ? '--fungus ' : '';
            $resume = $run_project->value('resume') ? '-resume ' : '';
            if ($run_project->value('genus')) {
                $genus_name = $run_project->value('genus_name');
                $genus = '--genus ' . $genus_name . ' ';
            } else {
                $genus = '';
            }
            if ($run_project->value('augustus_species')) {
                $augustus_species_name = $run_project->value('augustus_species_name');
                $augustus_species = '--augustus_species ' . $augustus_species_name . ' ';
            } else {
                $augustus_species = '';
            }
            /**
             * pipeline params database path
             */
            $pipeline_params = pipelineParams::find(1);
            $resfinder_db_path = $pipeline_params->resfinder_db_path;
            $nt_db_path = $pipeline_params->nt_db_path;
            $eggnog_db_path = $pipeline_params->eggnog_db_path;
            $kraken_db_path = $pipeline_params->kraken_db_path;
            $kofam_profile_path = $pipeline_params->kofam_profile_path;
            $kofam_kolist_path = $pipeline_params->kofam_kolist_path;
            $resfinder_db = $run_project->value('resfinder_db') ? '--resfinder_db ' . $resfinder_db_path . ' ' : '';
            $nt_db = $run_project->value('nt_db') ? '--nt_db ' . $nt_db_path . ' ' : '';
            $kraken_db = $run_project->value('kraken_db') ? '--kraken_db ' . $kraken_db_path . ' ' : '';
            $eggnog_db = $run_project->value('eggnog') ? '--eggnog_db ' . $eggnog_db_path . ' ' : '';
            $kofam_profile = $run_project->value('kofam_profile') ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
            $kofam_kolist = $run_project->value('kofam_kolist') ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';

            $first_sample = Samples::where('projects_id', $project_id)->first();
            $first_sample_id = $first_sample->id;
            $species_id = $first_sample->species_id;
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

            $sample = Samples::find($first_sample_id);
            $filename1 = $sample->filename1;
            $filename1 = $base_path . '' . $filename1;

            $sample->pairends ? $filename2 = $sample->filename2 : $filename2 = null;
            preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $filename1, $matches);
            $file_postfix = $matches[0];
            $file_prefix = Str::before($filename1, $file_postfix);
            $filename = str_replace($file_prefix, '*', $filename1);
            $replace_num_position = strrpos($filename, '1');
            $filename = substr_replace($filename, '[1,2]', $replace_num_position, 1);
            $filename = $base_path . '' . $filename;

            if ($filename2 != null) {
                //pairEnds
                $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '-profile docker,base ' . $resume . '--outdir results -w work';
            } else {
                //singleEnds
                $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads ' . '"' . $filename1 . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '--singleEnds -profile docker,base ' . $resume . '--outdir results -w work';
            }

            /**
             * jobs表中添加记录
             */
            $lab_id = Projects::where('id', $project_id)->value('labs_id');
            $user_name = Labs::where('id', $lab_id)->value('principleInvestigator');
            $user_id = User::where('name', $user_name)->value('id');
            if (Jobs::where('project_id', $project_id)->count() == 0) {
                Jobs::create([
                    'user_id' => $user_id,
                    'sample_id' => null,
                    'project_id' => $project_id,
                    'uuid' => 'default',
                    'current_uuid' => 'default',
                    'started' => '000',
                    'finished' => '000',
                    'command' => $cmd,
                    'status' => 0   // 0:have't started
                ]);
            } else {
                $job_id = Jobs::where('project_id', $project_id)->value('id');
                $job = Jobs::find($job_id);
                $job->user_id = $user_id;
                $job->project_id = $project_id;
                $job->sample_id = null;
                $job->started = '000';
                $job->finished = '000';
                $job->command = $cmd;
                $job->status = 0;
                $job->save();
            }
            RunProjPipeline::dispatch($project_id);
            return redirect('/executeProj/start?projectID=' . $project_id);
        }
        $pipelineParams = pipelineParams::find(1);
        $project_id = $request->input('projectID');
        $can_exec = Jobs::where('project_id', $project_id)->count() == 0 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 2 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 3;
        if (Execparams::where('project_id', $project_id)->get()->count() != 0) {
            $data = Execparams::where('project_id', $project_id);
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
            $augustus_species = $data->value('augustus_species');    //boolean
            $augustus_species_name = $data->value('augustus_species_name');    //string
            $resfinder_db = $data->value('resfinder_db');     //boolean
            $nt_db = $data->value('nt_db');     //boolean
            $kraken_db = $data->value('kraken_db');     //boolean
            $eggnog = $data->value('eggnog');    //boolean
            $kofam_profile = $data->value('kofam_profile');    //boolean
            $kofam_kolist = $data->value('kofam_kolist');     //boolean
            $augustus_species_lists = array(
                'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
            );
            return view('Pipeline.projPipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'project_id', 'pipelineParams', 'can_exec', 'augustus_species_lists'));
        } else {
            $ass = $cnv = $snv = $bulk = $saturation = $acquired = $saveTrimmed = $saveAlignedIntermediates = $resume = $euk = $fungus = $genus = $augustus_species = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = false;
            $genus_name = $augustus_species_name = null;
            $augustus_species_lists = array(
                'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
            );
            return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'project_id', 'pipelineParams', 'can_exec', 'augustus_species_lists'));
        }
    }

    public function start(Request $request)
    {
        $pipelineParams = pipelineParams::find(1);
        $project_id = $request->input('projectID');
        $data = Execparams::where('project_id', $project_id);
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
        $augustus_species = $data->value('augustus_species');    //string
        $augustus_species_name = $data->value('augustus_species_name');    //string
        $resfinder_db = $data->value('resfinder_db');     //boolean
        $nt_db = $data->value('nt_db');     //boolean
        $kraken_db = $data->value('kraken_db');     //boolean
        $eggnog = $data->value('eggnog');    //boolean
        $kofam_profile = $data->value('kofam_profile');    //boolean
        $kofam_kolist = $data->value('kofam_kolist');     //boolean
        return view('Pipeline.projPipelineStart', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'project_id', 'pipelineParams'));
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
