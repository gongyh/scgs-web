<?php

namespace App\Jobs;

use App\Execparams;
use App\Species;
use App\Samples;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        //
        $run_sample = $execparams->where('samples_id', $this->run_sample_id);
        $ass = $run_sample->value('ass');
        $cnv = $run_sample->value('cnv');
        $snv = $run_sample->value('snv');
        $genus = $run_sample->value('genus');
        $genus_name = $run_sample->value('genus_name');
        $resfinder_db = $run_sample->value('resfinder_db');
        $nt_db = $run_sample->value('nt_db');
        $kraken_db = $run_sample->value('kraken_db');
        $eggnog = $run_sample->value('eggnog');
        $kofam_profile = $run_sample->value('kofam_profile');
        $kofam_kolist = $run_sample->value('kofam_kolist');
        $species_id = Samples::where('id', $this->run_sample_id)->value('species_id');
        if (isset($species_id)) {
            $fasta = $gff = true;
            $fasta_path = Species::where('id', $species_id)->value('fasta');
            $gff_path = Species::where('id', $species_id)->value('gff');
        } else {
            $fasta = $gff = false;
        }
    }
}
