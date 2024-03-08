<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Execparams;
use App\PipelineParams;
use App\Projects;
use App\Labs;
use App\Samples;
use App\Species;
use App\Jobs;
use App\Weblog;
use App\Jobs\RunPipeline;
use App\Jobs\RunProjPipeline;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use stdClass;

class ExecparamsController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            $samples = new Samples();
            $PipelineParams = PipelineParams::find(1);
            $execute_params = array();

            //Get form post data
            $request->has('sampleID') ? $sample_id = $request->input('sampleID') : $project_id = $request->input('projectID');
            $switch = array(
                'ass', 'snv', 'cnv', 'bulk', 'nanopore', 'saturation', 'no_normalize', 'acquired', 'split', 'split_euk', 'acdc', 'blob',
                'saveTrimmed', 'saveAlignedIntermediates', 'graphbin', 'euk', 'fungus', 'resume', 'genus', 'augustus_species', 'point',
                'nt', 'eggnog', 'kraken', 'kofam_profile', 'checkm2', 'eukcc_db', 'gtdb'
            );
            foreach ($switch as $i) {
                if ($request->input($i) == $i) {
                    $execute_params[$i] = true;
                } else {
                    $execute_params[$i] = false;
                }
            }
            if ($request->has('reference_genome')) {
                if ($request->input('reference_genome') == 'denovo') {
                    $execute_params['reference_genome'] = 'denovo';
                } else {
                    $reference_genome_id = $request->input('reference_genome');
                    $reference_genome = Species::where('id', $reference_genome_id)->value('name');
                    $execute_params['reference_genome'] = $reference_genome;
                }
            }
            $request->input('kofam_profile') == 'kofam_profile' ? $execute_params['kofam_kolist'] = true : $execute_params['kofam_kolist'] = false;
            if ($execute_params['genus']) {
                $this->validate($request, [
                    'genus_name' => 'required|max:200'
                ]);
                $genus_name = $request->input('genus_name');
                $execute_params['genus_name'] = $genus_name;
            } else {
                $execute_params['genus_name'] = '';
            }
            if ($execute_params['augustus_species']) {
                $execute_params['augustus_species_name'] = $request->input('augustus_species_name');
            } else {
                $execute_params['augustus_species_name'] = '';
            }
            if ($execute_params['split']) {
                $execute_params['split_bac_level'] = $request->input('split_bac_level');
                $execute_params['split_euk_level'] = $request->input('split_euk_level');
            } else {
                $execute_params['split_bac_level'] = '';
                $execute_params['split_euk_level'] = '';
            }
            $execute_params['ver'] = $request->input('ver');
            $execute_params_json = json_encode($execute_params);
            if ($request->input('sampleID')) {
                if (Execparams::where('samples_id', $sample_id)->get()->count() == 0) {
                    Execparams::create([
                        'samples_id' => $sample_id,
                        'project_id' => null,
                        'execute_params' => $execute_params_json,
                    ]);
                } else {
                    $id = Execparams::where('samples_id', $sample_id)->value('id');
                    $execparams = Execparams::find($id);
                    $execparams->execute_params = $execute_params_json;
                    $execparams->save();
                }
            } else {
                if (Execparams::where('project_id', $project_id)->get()->count() == 0) {
                    Execparams::create([
                        'samples_id' => null,
                        'project_id' => $project_id,
                        'execute_params' => $execute_params_json,
                    ]);
                } else {
                    $id = Execparams::where('project_id', $project_id)->value('id');
                    $execparams = Execparams::find($id);
                    $execparams->execute_params = $execute_params_json;
                    $execparams->save();
                }
            }
            /**
             * Execparams reading, concat command
             */
            $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
            $execparams = new Execparams();
            $run_sample = $request->input('sampleID') ? $execparams->where('samples_id', $sample_id) : $execparams->where('project_id', $project_id);
            $execute_params_json = $run_sample->value('execute_params');
            $execute_params = json_decode($execute_params_json);
            $ass = $execute_params->ass ? '--ass ' : '';
            $cnv = $execute_params->cnv ? '--cnv ' : '';
            $snv = $execute_params->snv ? '--snv ' : '';
            $bulk = $execute_params->bulk ? '--bulk ' : '';
            $acdc = $execute_params->acdc ? '--acdc ' : '';
            $nanopore = $execute_params->nanopore ? '--nanopore ' : '';
            $no_normalize = $execute_params->no_normalize ? '--no_normalize ' : '';
            $saturation = $execute_params->saturation ? '--saturation ' : '';
            $acquired = $execute_params->acquired ? '--acquired ' : '';
            $split = $execute_params->split ? '--split ' : '';
            $split_euk = $execute_params->split_euk ? '--split_euk ' : '';
            $graphbin = $execute_params->graphbin ? '--graphbin ' : '';
            $saveTrimmed = $execute_params->saveTrimmed ? '--saveTrimmed ' : '';
            $saveAlignedIntermediates = $execute_params->saveAlignedIntermediates ? '--saveAlignedIntermediates ' : '';
            $euk = $execute_params->euk ? '--euk ' : '';
            $fungus = $execute_params->fungus ? '--fungus ' : '';
            $resume = $execute_params->resume ? '-resume ' : '';
            if ($execute_params->genus) {
                $genus_name = $execute_params->genus_name;
                $genus = '--genus ' . $genus_name . ' ';
            } else {
                $genus = '';
            }
            if ($execute_params->augustus_species) {
                $augustus_species = '--augustus_species ' . $execute_params->augustus_species_name . ' ';
            } else {
                $augustus_species = '';
            }
            if ($execute_params->split) {
                if ($execute_params->split_bac_level == 'genus') {
                    $split_bac_level = '';
                } else {
                    $split_bac_level = '--split_bac_level ' . $execute_params->split_bac_level . ' ';
                }
                if ($execute_params->split_euk_level == 'genus') {
                    $split_euk_level = '';
                } else {
                    $split_euk_level = '--split_euk_level ' . $execute_params->split_euk_level . ' ';
                }
            } else {
                $split_bac_level = '';
                $split_euk_level = '';
            }
            /**
             * Pipeline params database path
             */
            $pipeline_params = PipelineParams::find(1);
            $nt_db = $pipeline_params->nt_db_path;
            $blob_path = $pipeline_params->blob_path;
            $eggnog_db = $pipeline_params->eggnog_db_path;
            $kraken2_db = $pipeline_params->kraken2_db_path;
            $kofam_profile_path = $pipeline_params->kofam_profile_path;
            $kofam_kolist_path = $pipeline_params->kofam_kolist_path;
            $checkm2_db_path = $pipeline_params->checkm2_db_path;
            $eukcc_db_path = $pipeline_params->eukcc_db_path;
            $gtdb_path = $pipeline_params->gtdb_path;
            $point = $execute_params->point ? '--point ' : '';
            $nt = $execute_params->nt ? '--blastn --nt_db ' . $nt_db . ' ' : '';
            $blob = $execute_params->blob ? '--blob --blob_db ' . $blob_path . ' ' : '';
            $kraken = $execute_params->kraken ? '--kraken --kraken_db ' . $kraken2_db . ' ' : '';
            $eggnog = $execute_params->eggnog ? '--eggnog --eggnog_db ' . $eggnog_db . ' ' : '';
            $kofam_profile = $execute_params->kofam_profile ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
            $kofam_kolist = $execute_params->kofam_kolist ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';
            $checkm2_db = $execute_params->checkm2 ? '--checkm2 --checkm2_db ' . $checkm2_db_path . ' ' : '';
            $eukcc_db = $execute_params->eukcc_db ? '--eukcc_db ' . $eukcc_db_path . ' ' : '';
            $gtdbtk = $execute_params->gtdb ? '--gtdbtk --gtdb' . $gtdb_path . ' ' : '';

            if ($request->input('sampleID')) {
                $sampleID = $request->input('sampleID');
                $projectID = Samples::where('id', $sampleID)->value('projects_id');
                $accession = Projects::where('id', $projectID)->value('doi');
                $labID = Projects::where('id', $projectID)->value('labs_id');
                $user = Labs::where('id', $labID)->value('principleInvestigator');
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
                if (strpos($filename1, 'R1')) {
                    $filename = str_replace('R1', 'R[1,2]', $filename1);
                } elseif (strpos($filename1, '_1')) {
                    $filename = str_replace('_1', '_[1,2]', $filename1);
                } elseif (strpos($filename1, '.1')) {
                  $filename = str_replace('.1', '.[1,2]', $filename1);
              }

                // Save records as sample username + sampleLabel
                $sample_label = Samples::where('id', $sample_id)->value('sampleLabel');
                $sample_user_name = Auth::user()->name;

                if ($filename2 != null) {
                    // PairEnds
                    $cmd = '--reads "' . $base_path . $accession . '/' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $nanopore . $no_normalize . $saturation . $acquired . $split . $split_euk . $split_bac_level . $split_euk_level . $graphbin . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $acdc . $point . $nt .
                        $blob . $eggnog . $kraken . $kofam_profile . $kofam_kolist . $checkm2_db . $eukcc_db . $gtdbtk . $resume . '--outdir results -w work';
                } else {
                    // SingleEnds
                    $cmd = '--reads "' . $base_path . $accession . '/' . $filename1 . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $nanopore . $no_normalize . $saturation . $acquired . $split . $split_euk . $split_bac_level . $split_euk_level . $graphbin . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $acdc . $point . $nt . $blob . $eggnog . $kraken . $kofam_profile . $kofam_kolist . $checkm2_db . $eukcc_db . $gtdbtk . '--single_end ' . $resume . '--outdir results -w work';
                }
            } else {
                $projectID = $request->input('projectID');
                $accession = Projects::where('id', $projectID)->value('doi');
                $labID = Projects::where('id', $projectID)->value('labs_id');
                $user = Labs::where('id', $labID)->value('principleInvestigator');
                $first_sample = Samples::where('projects_id', $projectID)->first();
                $first_sample_id = $first_sample->id;
                $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
                if ($execute_params->reference_genome == 'denovo') {
                    $fasta = $gff = '';
                } else {
                    $reference_genome = $execute_params->reference_genome;
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
                $filename_single = $filename;
                $replace_num_position = strrpos($filename, '1');
                $filename = substr_replace($filename, '[1,2]', $replace_num_position, 1);

                if ($filename2 != null) {
                    // Paired-End
                    $cmd = '--reads "' . $base_path . $project_accession . '/' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $nanopore . $no_normalize . $saturation . $acquired . $split . $split_euk . $split_bac_level . $graphbin . $split_euk_level . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $augustus_species . $acdc . $point . $nt . $blob . $eggnog . $kraken . $kofam_profile . $kofam_kolist . $checkm2_db . $eukcc_db . $gtdbtk . $resume . '--outdir results -w work';
                } else {
                    // Single
                    $cmd = '--reads "' . $base_path . $project_accession . '/' . $filename_single . '" ' . $fasta . $gff . $ass . $cnv . $snv . $bulk . $nanopore . $no_normalize . $saturation . $acquired . $split . $split_euk . $split_bac_level . $graphbin . $split_euk_level . $saveTrimmed . $saveAlignedIntermediates . $euk . $fungus . $genus . $acdc .$augustus_species . $point . $nt . $blob . $eggnog . $kraken . $kofam_profile . $kofam_kolist . $checkm2_db . $eukcc_db . $gtdbtk . '--single_end ' . $resume . '--outdir results -w work';
                }
            }

            /**
             * Add jobs records and change sample status
             */
            if ($request->has('sampleID')) {
                $sample_id = $request->input('sampleID');
                $sample = Samples::find($sample_id);
                # change sample status
                $sample['status'] = 4; # 4: queueing
                $sample->save();
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
                    $job->current_uuid = 'restart';
                    $job->started = '000';
                    $job->finished = '000';
                    $job->command = $cmd;
                    $job->status = 0;
                    $job->save();
                }
                RunPipeline::dispatch($sample_id);
                return redirect('/execute/start?sampleID=' . $sample_id);
            } else {
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
                    $job->current_uuid = 'restart';
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
        $PipelineParams = PipelineParams::find(1);
        $ranks = array('domain', 'order', 'phylum', 'class', 'genus', 'species');
        $Tswitch = array('ass', 'acquired', 'resume', 'point', 'blob', 'nt', 'kraken', 'eggnog', 'kofam_profile', 'kofam_kolist', 'checkm2');
        $Fswitch = array(
            'snv', 'cnv', 'bulk', 'nanopore', 'saturation', 'no_normalize', 'split', 'split_euk', 'acdc',
            'saveTrimmed', 'saveAlignedIntermediates', 'graphbin', 'euk', 'fungus', 'genus', 'augustus_species', 'eukcc_db', 'gtdb'
        );
        $Nswitch = array('augustus_species_name', 'genus_name', 'split_bac_level', 'split_euk_level');
        $checkm_genus = Storage::get('checkm_genus.txt');
        $checkm_genus = explode("\r\n", $checkm_genus);
        $genus_list = array();
        foreach ($checkm_genus as $checkm) {
            $checkm = str_replace(" ", "", $checkm);
            $checkm = preg_replace("/\\d+/", '', $checkm);
            array_push($genus_list, $checkm);
        }
        $augustus_species_lists = array(
            'Conidiobolus_coronatus', 'E_coli_K12', 'Xipophorus_maculatus', 'adorsata', 'aedes', 'amphimedon', 'ancylostoma_ceylanicum', 'anidulans', 'arabidopsis', 'aspergillus_fumigatus', 'aspergillus_nidulans', 'aspergillus_oryzae', 'aspergillus_terreus', 'b_pseudomallei', 'bombus_impatiens1', ' bombus_terrestris2', 'botrytis_cinerea', 'brugia', 'c_elegans_trsk', 'cacao', 'caenorhabditis', 'camponotus_floridanus', 'candida_albicans', 'candida_guilliermondii', 'candida_tropicalis', 'chaetomium_globosum', 'chicken', 'chiloscyllium', 'chlamy2011', 'chlamydomonas', 'chlorella', 'ciona', 'coccidioides_immitis', 'coprinus', 'coprinus_cinereus', 'coyote_tobacco', 'cryptococcus', 'cryptococcus_neoformans_gattii', 'cryptococcus_neoformans_neoformans_B', 'cryptococcus_neoformans_neoformans_JEC21', 'culex', 'debaryomyces_hansenii', 'elephant_shark', 'encephalitozoon_cuniculi_GB', 'eremothecium_gossypii', 'fly', 'fly_exp', 'fusarium', 'fusarium_graminearum', 'galdieria', 'generic', 'heliconius_melpomene1', 'histoplasma', 'histoplasma_capsulatum', 'honeybee1', 'human', 'japaneselamprey', 'kluyveromyces_lactis', 'laccaria_bicolor', 'leishmania_tarentolae', 'lodderomyces_elongisporus', 'magnaporthe_grisea', 'maize', 'maize5', 'mnemiopsis_leidyi', 'nasonia', 'nematostella_vectensis', 'neurospora', 'neurospora_crassa', 'parasteatoda', 'pchrysosporium', 'pea_aphid', 'pfalciparum', 'phanerochaete_chrysosporium', 'pichia_stipitis', 'pisaster', 'pneumocystis', 'rhincodon', 'rhizopus_oryzae', 'rhodnius', 'rice', 's_aureus', 's_pneumoniae', 'saccharomyces', 'saccharomyces_cerevisiae_S288C', 'saccharomyces_cerevisiae_rm11-1a_1', 'schistosoma', 'schistosoma2', 'schizosaccharomyces_pombe', 'scyliorhinus', 'sealamprey', 'strongylocentrotus_purpuratus', 'sulfolobus_solfataricus', 'template_prokaryotic', 'tetrahymena', 'thermoanaerobacter_tengcongensis', 'tomato', 'toxoplasma', 'tribolium2012', 'trichinella', 'ustilago', 'ustilago_maydis', 'verticillium_albo_atrum1', 'verticillium_longisporum1', 'volvox', 'wheat', 'yarrowia_lipolytica', 'zebrafish'
        );
        if ($request->has('sampleID')) {
            $sample_id = $request->input('sampleID');
            $project_id = Samples::where('id', $sample_id)->value('projects_id');
            $can_exec = Jobs::where('sample_id', $sample_id)->count() == 0 || Jobs::where('sample_id', $sample_id)->orderBy('id', 'desc')->value('status') == 2 || Jobs::where('sample_id', $sample_id)->orderBy('id', 'desc')->value('status') == 3;
            if (Execparams::where('samples_id', $sample_id)->get()->count() != 0) {
                $execute_json = Execparams::where('samples_id', $sample_id)->value('execute_params');
                $eParams = json_decode($execute_json);
                return view('Pipeline.pipeline', compact('sample_id', 'project_id', 'PipelineParams', 'eParams', 'can_exec', 'augustus_species_lists', 'genus_list', 'ranks'));
            } else {
                $eParams = new stdClass();
                foreach ($Fswitch as $f) {
                    $eParams->$f = false;
                }
                foreach ($Tswitch as $t) {
                    $eParams->$t = true;
                }
                foreach ($Nswitch as $n) {
                    $eParams->$n = null;
                }
                return view('Pipeline.pipeline', compact('eParams', 'sample_id', 'project_id', 'PipelineParams', 'can_exec', 'augustus_species_lists', 'genus_list', 'ranks'));
            }
        } else {
            $project_id = $request->input('projectID');
            $can_exec = Jobs::where('project_id', $project_id)->count() == 0 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 2 || Jobs::where('project_id', $project_id)->orderBy('id', 'desc')->value('status') == 3;
            $species_list = Species::all();
            $first_sample = Samples::where('projects_id', $project_id)->first();
            $first_sample_species_id = $first_sample->species_id;
            $default_reference = isset($first_sample_species_id) ? Species::where('id', $first_sample_species_id)->value('name') : 'denovo';
            if (Execparams::where('project_id', $project_id)->get()->count() != 0) {
                $execute_json = Execparams::where('project_id', $project_id)->value('execute_params');
                $eParams = json_decode($execute_json);
                return view('Pipeline.pipeline', compact('project_id', 'PipelineParams', 'can_exec', 'augustus_species_lists', 'eParams', 'species_list', 'default_reference', 'genus_list', 'ranks'));
            } else {
                $eParams = new stdClass();
                foreach ($Fswitch as $f) {
                    $eParams->$f = false;
                }
                foreach ($Tswitch as $t) {
                    $eParams->$t = true;
                }
                foreach ($Nswitch as $n) {
                    $eParams->$n = null;
                }
                return view('Pipeline.pipeline', compact('eParams', 'project_id', 'PipelineParams', 'can_exec', 'augustus_species_lists', 'species_list', 'default_reference', 'genus_list', 'ranks'));
            }
        }
    }

    public function start(Request $request)
    {
        if ($request->isMethod('POST')) {
            $runName = $request->input('runName');
            $uuid = "";
            if (is_null($runName)) {
                return response()->json(['message' => 'Error: runName is not exist!'], 403);
            } else {
                if ((strlen($runName) == 41) && (substr($runName, 0, 5) == "uuid-")) {
                    $uuid = ltrim($runName, "uuid-");
                } else {
                    return response()->json(['message' => 'Error: incorrect runName!'], 403);
                }
            }
            $runId = $request->input('runId');
            $event = $request->input('event');
            $utcTime = $request->input('utcTime');
            $process = $request->input('trace.name', 'pipeline');
            $weblog = new Weblog;
            $weblog->runName = $uuid;
            $weblog->runId = $runId;
            $weblog->event = $event;
            $carbonDate = new Carbon($utcTime);
            $carbonDate->timezone = config('app.timezone', 'UTC');
            $weblog->utcTime = $carbonDate->toDateTimeString();
            $weblog->process = $process;
            $weblog->save();
            return response()->json(['msg' => 'Success!'], 200);
        } else {
            $PipelineParams = PipelineParams::find(1);
            $samples = new Samples();
            if ($request->input('sampleID')) {
                $sample_id = $request->input('sampleID');
                $data_json = Execparams::where('samples_id', $sample_id)->value('execute_params');
                $data = json_decode($data_json);
            } else {
                $project_id = $request->input('projectID');
                $data_json = Execparams::where('project_id', $project_id)->value('execute_params');
                $data = json_decode($data_json);
                $reference_genome = $data->reference_genome;
            }
            $ass = $data->ass;
            $cnv = $data->cnv;
            $snv = $data->snv;
            $bulk = $data->bulk;
            $nanopore = $data->nanopore;
            $saturation = $data->saturation;
            $acquired = $data->acquired;
            $split = $data->split;
            $saveTrimmed = $data->saveTrimmed;
            $saveAlignedIntermediates = $data->saveAlignedIntermediates;
            $no_normalize = $data->no_normalize;
            $euk = $data->euk;
            $fungus = $data->fungus;
            $resume = $data->resume;
            $genus = $data->genus;
            $genus_name = $data->genus_name;
            $augustus_species = $data->augustus_species;
            $augustus_species_name = $data->augustus_species;
            $point = $data->point;
            $graphbin = $data->graphbin;
            $acdc = $data->acdc;
            $nt_db = $data->nt;
            $kraken2_db = $data->kraken;
            $eggnog = $data->eggnog;
            $kofam_profile = $data->kofam_profile;
            $kofam_kolist = $data->kofam_kolist;
            $checkm2 = $data->checkm2;
            $eukcc_db = $data->eukcc_db;
            if ($request->has('sampleID')) {
                $project_id = Samples::where('id', $sample_id)->value('projects_id');
                return view('Pipeline.pipelineStart', compact('samples', 'ass', 'cnv', 'snv', 'bulk', 'point', 'nanopore', 'saturation', 'acquired', 'split', 'saveTrimmed', 'saveAlignedIntermediates', 'no_normalize', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'augustus_species', 'graphbin', 'acdc', 'augustus_species_name', 'nt_db', 'kraken2_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'checkm2', 'eukcc_db', 'sample_id', 'project_id', 'PipelineParams'));
            } else {
                return view('Pipeline.pipelineStart', compact('ass', 'cnv', 'snv', 'bulk', 'point', 'nanopore', 'saturation', 'acquired', 'split', 'saveTrimmed', 'saveAlignedIntermediates', 'no_normalize', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'reference_genome', 'augustus_species', 'graphbin', 'acdc', 'augustus_species_name', 'nt_db', 'kraken2_db',  'eggnog',  'kofam_profile', 'kofam_kolist', 'checkm2', 'eukcc_db', 'project_id', 'PipelineParams'));
            }
        }
    }

    public function get_status(Request $request)
    {
        if ($request->input('running_sample_id')) {
            $running_sample_id = $request->input('running_sample_id');
            $runName = Jobs::where('sample_id', $running_sample_id)->value('current_uuid');
            $runStatus = Jobs::where('sample_id', $running_sample_id)->value('status');
        } else {
            $running_project_id = $request->input('running_project_id');
            $runName = Jobs::where('project_id', $running_project_id)->value('current_uuid');
            $runStatus = Jobs::where('project_id', $running_project_id)->value('status');
        }
        if ($runName == 'restart') {
            $data = array();
            return response()->json(['code' => 202, 'data' => $data]);
        } else {
            $weblogs = Weblog::where('runName', $runName)->orderByDesc('created_at')->get();
            $data = array('weblogs' => $weblogs, 'runStatus' => $runStatus);
            return response()->json(['code' => 200, 'data' => $data]);
        }
    }
}
