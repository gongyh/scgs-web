<?php

namespace App\Jobs;

use App\Execparams;
use App\pipelineParams;
use App\Species;
use App\Samples;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RunPipeline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $run_sample_id;
    public function __construct($run_sample_id)
    {
        //
        $this->run_sample_id = $run_sample_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Execparams $execparams)
    {
        /*  Execparams */
        $run_sample = $execparams->where('samples_id', $this->run_sample_id);
        $ass = $run_sample->value('ass') ? '--ass ' : '';
        $cnv = $run_sample->value('cnv') ? '--cnv ' : '';
        $snv = $run_sample->value('snv') ? '--snv ' : '';
        if ($run_sample->value('genus')) {
            $genus_name = $run_sample->value('genus_name');
            $genus = '--genus ' . $genus_name . ' ';
        } else {
            $genus = '';
        }

        //Pipeline Params
        $pipeline_params = pipelineParams::find(1);
        $resfinder_db_path = $pipeline_params->resfinder_db_path;
        $nt_db_path = $pipeline_params->nt_db_path;
        $eggnog_db_path = $pipeline_params->eggnog_db_path;
        $kraken_db_path = $pipeline_params->kraken_db_path;
        $kofam_profile_path = $pipeline_params->kofam_profile_path;
        $kofam_kolist_path = $pipeline_params->kofam_kolist_path;


        $resfinder_db = $run_sample->value('resfinder_db') ? '--resfinder_db ' . $resfinder_db_path . ' ' : '';
        $nt_db = $run_sample->value('nt_db') ? '--nt_db ' . $nt_db_path . ' ' : '';
        $kraken_db = $run_sample->value('kraken_db') ? '--kraken_db ' . $kraken_db_path . ' ' : '';
        $eggnog_db = $run_sample->value('eggnog') ? '--eggnog_db ' . $eggnog_db_path . ' ' : '';
        $kofam_profile = $run_sample->value('kofam_profile') ? '--kofam_profile ' . $kofam_profile_path . ' ' : '';
        $kofam_kolist = $run_sample->value('kofam_kolist') ? '--kofam_kolist ' . $kofam_kolist_path . ' ' : '';

        //Species
        $species_id = Samples::where('id', $this->run_sample_id)->value('species_id');
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

        //Samples
        $sample = Samples::find($this->run_sample_id);
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
            $cmd = 'cd /mnt/scc8t/zhousq/ && /opt/images/bin/nextflow run nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $genus . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '--saturation --acquired -profile docker,base -resume --outdir results -w work';
            system($cmd);
        } else {
            //singleEnds
            $cmd = 'cd /mnt/scc8t/zhousq/ && /opt/images/bin/nextflow run nf-core-scgs ' . '--reads ' . '"' . $filename . '" ' . $fasta . $gff . $ass . $cnv . $snv . $genus . $resfinder_db . $nt_db . $eggnog_db . $kraken_db . $kofam_profile . $kofam_kolist . '--singleEnds --saturation --acquired -profile docker,base -resume --outdir results -w work';
            system($cmd);
        }
    }
}
