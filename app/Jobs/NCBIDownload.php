<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class NCBIDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $username;
    private $sra_id;
    public function __construct($username, $sra_id)
    {
        //
        $this->username = $username;
        $this->sra_id = $sra_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $user_dir = $base_path . 'meta-data/' . $this->username;
        $fastq_dump = '/mnt/scc8t/zhousq/Minoconda3/bin/parallel-fastq-dump --sra-id ' . $this->sra_id . ' --threads 4 --outdir ' . $user_dir . '/ --split-files --gzip';
        system($fastq_dump);
    }
}
