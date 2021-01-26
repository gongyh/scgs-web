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

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            //Get form post data
            $samples = new Samples();
            $pipelineParams = pipelineParams::find(1);
            $request->has('sampleID') ? $sample_id = $request->input('sampleID') : $project_id = $request->input('projectID');
            $ass = $request->input('ass') == 'ass' ? true : false;
            $snv = $request->input('snv') == 'snv' ? true : false;
            $cnv = $request->input('cnv') == 'cnv' ? true : false;
            $bulk = $request->input('bulk') == 'bulk' ? true : false;
            $saturation = $request->input('saturation') == 'saturation' ? true : false;
            $acquired = $request->input('acquired') == 'acquired' ? true : false;
            $saveTrimmed = $request->input('saveTrimmed') == 'saveTrimmed' ? true : false;
            $saveAlignedIntermediates = $request->input('saveAlignedIntermediates') == 'saveAlignedIntermediates' ? true : false;
            if($request->has('reference_genome')){
                if ($request->input('reference_genome') == 'denovo') {
                    $reference_genome = 'denovo';
                } else {
                    $reference_genome_id = $request->input('reference_genome');
                    $reference_genome = Species::where('id', $reference_genome_id)->value('name');
                }
            }
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
            $eukcc_db = $request->input('eukcc_db') == 'eukcc_db' ? true : false;
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
            if($request->input('sampleID')){
                if (Execparams::where('samples_id', $sample_id)->get()->count() == 0) {
                    Execparams::create([
                        'samples_id' => $sample_id,
                        'project_id' => null,
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
                        'reference_genome' => null,
                        'augustus_species' => $augustus_species,
                        'augustus_species_name' => $augustus_species_name,
                        'resfinder_db' => $resfinder_db,
                        'nt_db' => $nt_db,
                        'eggnog' => $eggnog,
                        'kraken_db' => $kraken_db,
                        'kofam_profile' => $kofam_profile,
                        'kofam_kolist' => $kofam_kolist,
                        'eukcc_db' => $eukcc_db
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
                    $execparams->reference_genome = null;
                    $execparams->augustus_species = $augustus_species;
                    $execparams->augustus_species_name = $augustus_species_name;
                    $execparams->resfinder_db = $resfinder_db;
                    $execparams->kraken_db = $kraken_db;
                    $execparams->nt_db = $nt_db;
                    $execparams->eggnog = $eggnog;
                    $execparams->kofam_profile = $kofam_profile;
                    $execparams->kofam_kolist = $kofam_kolist;
                    $execparams->eukcc_db = $eukcc_db;
                    $execparams->save();
                }
            }else{
                if (Execparams::where('project_id', $project_id)->get()->count() == 0) {
                    Execparams::create([
                        'samples_id' => null,
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
                        'reference_genome' => $reference_genome,
                        'augustus_species' => $augustus_species,
                        'augustus_species_name' => $augustus_species_name,
                        'resfinder_db' => $resfinder_db,
                        'nt_db' => $nt_db,
                        'eggnog' => $eggnog,
                        'kraken_db' => $kraken_db,
                        'kofam_profile' => $kofam_profile,
                        'kofam_kolist' => $kofam_kolist,
                        'eukcc_db' => $eukcc_db
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
                    $execparams->reference_genome = $reference_genome;
                    $execparams->augustus_species = $augustus_species;
                    $execparams->augustus_species_name = $augustus_species_name;
                    $execparams->resfinder_db = $resfinder_db;
                    $execparams->kraken_db = $kraken_db;
                    $execparams->nt_db = $nt_db;
                    $execparams->eggnog = $eggnog;
                    $execparams->kofam_profile = $kofam_profile;
                    $execparams->kofam_kolist = $kofam_kolist;
                    $execparams->eukcc_db = $eukcc_db;
                    $execparams->save();
                }
            }

            /**
             * Execparams reading, concat command
             */
            $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
            $execparams = new Execparams();
            $run_sample = $request->input('sampleID') ? $execparams->where('samples_id', $sample_id) : $execparams->where('project_id', $project_id);
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
            if ($run_sample->value('augustus_species')) {
                $augustus_species_name = $run_sample->value('augustus_species_name');
                $augustus_species = '--augustus_species ' . $augustus_species_name . ' ';
            } else {
                $augustus_species = '';
            }
            /**
             * Pipeline params database path
             */
            $pipeline_params = pipelineParams::find(1);
            $resfinder_db_path = $pipeline_params->resfinder_db_path;
            $nt_db_path = $pipeline_params->nt_db_path;
            $eggnog_db_path = $pipeline_params->eggnog_db_path;
            $kraken_db_path = $pipeline_params->kraken_db_path;
            $kofam_profile_path = $pipeline_params->kofam_profile_path;
            $kofam_kolist_path = $pipeline_params->kofam_kolist_path;
            $eukcc_db_path = $pipeline_params->eukcc_db_path;
            $resfinder_db = $run_sample->value('resfinder_db') ? '--resfinder_db ' . $resfinder_db_path . ' ' : '';
            $nt_db = $run_sample->value('nt_db') ? '--nt_db ' . $nt_db_path . ' ' : '';
            $kraken_db = $run_sample->value('kraken_db') ? '--kraken_db ' . $kraken_db_path . ' ' : '';
            $eggnog_db = $run_sample->value('eggnog') ? '--eggnog_db ' . $eggnog_db_path . ' ' : '';
            $kofam_profile = $run_sample->value('kofam_profile') ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
            $kofam_kolist = $run_sample->value('kofam_kolist') ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';
            $eukcc_db = $run_sample->value('eukcc_db') ? '--eukcc_db ' . $eukcc_db_path . ' ' : '';

            if($request->input('sampleID')){
                $sampleID = $request->input('sampleID');
                $projectID = Samples::where('id',$sampleID)->value('projects_id');
                $accession = Projects::where('id', $projectID)->value('doi');
                $labID = Projects::where('id',$projectID)->value('labs_id');
                $user = Labs::where('id',$labID)->value('principleInvestigator');
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

                $sample->pairends ? $filename2 = $sample->filename2 : $filename2 = null;
                if (strpos($filename1, '_R1')) {
                    $filename = str_replace('_R1', '_R[1,2]', $filename1);
                } elseif (strpos($filename1, '_1')) {
                    $filename = str_replace('_1', '_[1,2]', $filename1);
                }

                // Save records as sample username + sampleLabel
                $sample_label = Samples::where('id', $sample_id)->value('sampleLabel');
                $sample_user_name = Auth::user()->name;

                if ($filename2 != null) {
                    // PairEnds
                    $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads "' . $base_path . $accession . '/' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $eukcc_db . '-profile docker,base ' . $resume . '--outdir results -w work';
                } else {
                    // SingleEnds
                    $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads "' . $base_path . $accession . '/' .$filename1 . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $eukcc_db . '--singleEnds -profile docker,base ' . $resume . '--outdir results -w work';
                }

            }else{
                $projectID = $request->input('projectID');
                $accession = Projects::where('id', $projectID)->value('doi');
                $labID = Projects::where('id', $projectID)->value('labs_id');
                $user = Labs::where('id',$labID)->value('principleInvestigator');
                $first_sample = Samples::where('projects_id', $projectID)->first();
                $first_sample_id = $first_sample->id;
                $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
                if ($run_sample->value('reference_genome') == 'denovo') {
                    $fasta = $gff = '';
                }else {
                    $reference_genome = $run_sample->value('reference_genome');
                    $fasta_path = Species::where('name', $reference_genome)->value('fasta');
                    $gff_path = Species::where('name', $reference_genome)->value('gff');
                    $fasta_path = $base_path . '' . $fasta_path;
                    $gff_path = $base_path . '' . $gff_path;
                    $fasta = '--fasta ' . $fasta_path . ' ';
                    $gff = '--gff ' . $gff_path . ' ';
                }

                $sample = Samples::find($first_sample_id);
                $filename1 = $sample->filename1;
                $project_accession = Projects::where('id', $project_id)->value('doi');

                $sample->pairends ? $filename2 = $sample->filename2 : $filename2 = null;
                preg_match('/(_trimmed)?(_combined)?(\.R1)?(_1)?(_R1)?(\.1_val_1)?(_R1_val_1)?(\.fq)?(\.fastq)?(\.gz)?$/', $filename1, $matches);
                $file_postfix = $matches[0];
                $file_prefix = Str::before($filename1, $file_postfix);
                $filename = str_replace($file_prefix, '*', $filename1);
                $replace_num_position = strrpos($filename, '1');
                $filename = substr_replace($filename, '[1,2]', $replace_num_position, 1);

                if ($filename2 != null) {
                    // Paired-End
                    $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads "' . $base_path . $project_accession . '/' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $eukcc_db . '-profile docker,base ' . $resume . '--outdir results -w work';
                } else {
                    // Single
                    $cmd = '/opt/images/bin/nextflow run /opt/images/nf-core-scgs ' . '--reads "' . $base_path . $project_accession . '/' . $filename1 . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $saturation . $acquired . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . $eukcc_db . '--singleEnds -profile docker,base ' . $resume . '--outdir results -w work';
                }
            }

            /**
             * Jobs add records
             */
            if($request->has('sampleID')){
                $sample_id = $request->input('sampleID');
                $project_id = Samples::where('id', $sample_id)->value('projects_id');
                $lab_id = Projects::where('id', $project_id)->value('labs_id');
                $user_name = Labs::where('id', $lab_id)->value('principleInvestigator');
                $user_id = User::where('name', $user_name)->value('id');
                if (Jobs::where('sample_id', $sample_id)->count() == 0) {
                    Jobs::create([
                        'user_id' => $user_id,
                        'sample_id' => $sample_id,
                        'project_id' => null,
                        'uuid' => 'default',
                        'current_uuid' => 'default',
                        'started' => '000',
                        'finished' => '000',
                        'command' => $cmd,
                        'status' => 0   // 0:Have't started
                    ]);
                } else {
                    $job_id = Jobs::where('sample_id', $sample_id)->value('id');
                    $job = Jobs::find($job_id);
                    $job->user_id = $user_id;
                    $job->sample_id = $sample_id;
                    $job->project_id = null;
                    $job->started = '000';
                    $job->finished = '000';
                    $job->command = $cmd;
                    $job->status = 0;
                    $job->save();
                }
                RunPipeline::dispatch($sample_id);
                return redirect('/execute/start?sampleID=' . $sample_id);
            }else{
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
                        'status' => 0   // 0:Have't started
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
                return redirect('/execute/start?projectID=' . $project_id);
            }
        }
        $pipelineParams = pipelineParams::find(1);
        if($request->has('sampleID')){
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
                $augustus_species = $data->value('augustus_species');    //boolean
                $augustus_species_name = $data->value('augustus_species_name');    //string
                $resfinder_db = $data->value('resfinder_db');     //boolean
                $nt_db = $data->value('nt_db');     //boolean
                $kraken_db = $data->value('kraken_db');     //boolean
                $eggnog = $data->value('eggnog');    //boolean
                $kofam_profile = $data->value('kofam_profile');    //boolean
                $kofam_kolist = $data->value('kofam_kolist');     //boolean
                $eukcc_db = $data->value('eukcc_db');     //boolean
                $augustus_species_lists = array(
                    'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                    'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
                );
                return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db','sample_id', 'pipelineParams', 'can_exec', 'augustus_species_lists'));
            } else {
                $ass = $cnv = $snv = $bulk = $saturation = $acquired = $saveTrimmed = $saveAlignedIntermediates = $resume = $euk = $fungus = $genus = $augustus_species = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = $eukcc_db = false;
                $genus_name = $augustus_species_name = null;
                $augustus_species_lists = array(
                    'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis','laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
                );
                return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db','sample_id', 'pipelineParams', 'can_exec', 'augustus_species_lists'));
            }
        }else{
            $project_id = $request->input('projectID');
            $can_exec = Jobs::where('project_id', $project_id)->count() == 0 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 2 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 3;
            $species_list = Species::all();
            $first_sample = Samples::where('projects_id', $project_id)->first();
            $first_sample_species_id = $first_sample->species_id;
            $default_reference = isset($first_sample_species_id) ? Species::where('id', $first_sample_species_id)->value('name') : 'denovo';
            if (Execparams::where('project_id', $project_id)->get()->count() != 0) {
                $data = Execparams::where('project_id', $project_id);
                $ass = $data->value('ass');
                $cnv = $data->value('cnv');
                $snv = $data->value('snv');
                $bulk = $data->value('bulk');
                $saturation = $data->value('saturation');
                $acquired = $data->value('acquired');
                $saveTrimmed = $data->value('saveTrimmed');
                $saveAlignedIntermediates = $data->value('saveAlignedIntermediates');
                $euk = $data->value('euk');
                $fungus = $data->value('fungus');
                $resume = $data->value('resume');
                $genus = $data->value('genus');
                $genus_name = $data->value('genus_name');
                $augustus_species = $data->value('augustus_species');
                $augustus_species_name = $data->value('augustus_species_name');
                $resfinder_db = $data->value('resfinder_db');
                $nt_db = $data->value('nt_db');
                $kraken_db = $data->value('kraken_db');
                $eggnog = $data->value('eggnog');
                $kofam_profile = $data->value('kofam_profile');
                $kofam_kolist = $data->value('kofam_kolist');
                $eukcc_db = $data->value('eukcc_db');
                $augustus_species_lists = array(
                    'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                    'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
                );
                return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db','project_id', 'pipelineParams', 'can_exec', 'augustus_species_lists', 'species_list', 'default_reference'));
            } else {
                $ass = $cnv = $snv = $bulk = $saturation = $acquired = $saveTrimmed = $saveAlignedIntermediates = $resume = $euk = $fungus = $genus = $augustus_species = $resfinder_db = $nt_db = $kraken_db = $eggnog = $kofam_profile = $kofam_kolist = $eukcc_db = false;
                $genus_name = $augustus_species_name = null;
                $species_list = Species::all();
                $augustus_species_lists = array(
                    'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis',
                    'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
                );
                return view('Pipeline.pipeline', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db', 'project_id', 'pipelineParams', 'can_exec', 'augustus_species_lists', 'species_list', 'default_reference'));
            }
        }
    }

    public function start(Request $request)
    {
        $pipelineParams = pipelineParams::find(1);
        $samples = new Samples();
        if($request->input('sampleID')){
            $sample_id = $request->input('sampleID');
            $data = Execparams::where('samples_id', $sample_id);
        }else{
            $project_id = $request->input('projectID');
            $data = Execparams::where('project_id', $project_id);
            $reference_genome = $data->value('reference_genome');
        }
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
        $eukcc_db = $data->value('eukcc_db');     //boolean
        if($request->has('sampleID')){
            return view('Pipeline.pipelineStart', compact('samples', 'ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db','sample_id', 'pipelineParams'));
        }else{
            return view('Pipeline.pipelineStart', compact('ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'reference_genome', 'augustus_species', 'augustus_species_name', 'resfinder_db', 'nt_db', 'kraken_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'eukcc_db','project_id', 'pipelineParams'));
        }
    }

    public function ajax(Request $request)
    {
        if($request->input('sampleID')){
            $running_sample_id = $request->input('running_sample_id');
            $uuid = Jobs::where([['sample_id', '=', $running_sample_id], ['status', '=', 1]])->value('uuid');
            $project_id = Samples::where('id', $running_sample_id)->value('projects_id');
            $project_accession = Projects::where('id', $project_id)->value('doi');
        }else{
            $running_project_id = $request->input('running_project_id');
            $uuid = Jobs::where([['project_id', '=', $running_project_id], ['status', '=', 1]])->value('uuid');
            $project_accession = Projects::where('id', $running_project_id)->value('doi');
        }
        $nextflow_log_path = $project_accession . '/' . $uuid . '/.nextflow.log';
        if (Storage::disk('local')->exists($nextflow_log_path)) {
            $data = Storage::get($nextflow_log_path);
            return response()->json(['code' => '200', 'data' => $data]);
        } else {
            return response()->json(['code' => '404', 'data' => '']);
        }
    }
}
