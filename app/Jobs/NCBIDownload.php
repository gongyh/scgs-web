<?php

namespace App\Jobs;

use App\Jobs;
use App\Ncbiupload;
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
    public function handle(Jobs $job)
    {
        //Get job uuid
        $job_rawbody = $this->job->getRawBody();
        $job_rawbody = json_decode($job_rawbody, true);
        $job_uuid = $job_rawbody['uuid'];

        $ncbi_id = Ncbiupload::where([['user', $this->username],['sra_id', $this->sra_id],['uuid','default']])->value('id');
        $current_job = Ncbiupload::find($ncbi_id);
        $current_job->uuid = $job_uuid;
        $current_job->save();

        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $user_dir = $base_path . 'meta-data/public';
        $fastq_dump = 'parallel-fastq-dump --sra-id ' . $this->sra_id . ' --threads 4 --outdir ' . $user_dir . '/ --split-files --gzip';
        system($fastq_dump);
        $cp_ncbi_file = 'cp ' . $base_path . 'meta-data/public/' . $this->sra_id . '_1.fastq.gz ' . $base_path . 'meta-data/' . $this->username . ' && cp ' . $base_path . 'meta-data/public/' . $this->sra_id . '_2.fastq.gz ' . $base_path . 'meta-data/' . $this->username;
        system($cp_ncbi_file);
    }
}
