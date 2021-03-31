<?php

namespace App\Jobs;


use App\Jobs;
use App\User;
use App\Samples;
use App\Projects;
use App\Weblog;
use App\PipelineParams;
use Illuminate\Support\Facades\Storage;
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
    public function handle(Jobs $job)
    {
        /**
         * Get uuid
         */
        $job_rawbody = $this->job->getRawBody();
        $job_rawbody = json_decode($job_rawbody, true);
        if (Jobs::where('sample_id', $this->run_sample_id)->count() == 0 || Jobs::where('sample_id', $this->run_sample_id)->value('uuid') == 'default') {
            $job_uuid = $job_rawbody['uuid'];
        } else {
            $job_uuid = Jobs::where('sample_id', $this->run_sample_id)->value('uuid');
        }
        /**
         * Get current time -- job beginning time
         */
        $started = time();
        /**
         * Jobs table update
         */
        $current_job_id = $job->where([['sample_id', '=', $this->run_sample_id], ['status', '=', 0]])->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->uuid = $job_uuid;
        $current_job->current_uuid = $job_rawbody['uuid'];
        $current_job->started = $started;
        $current_job->status = 1; //Running
        $current_job->save();
        /**
         * Samples table update
         */
        $sample = Samples::find($this->run_sample_id);
        $sample->status = 1;  //Running
        $sample->save();

        /**
         * Execute params
         */
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $sample_id = $current_job->sample_id;
        $project_id = Samples::where('id', $sample_id)->value('projects_id');
        $project_accession = Projects::where('id', $project_id)->value('doi');
        $pipeline_params = pipelineParams::find(1);
        $nextflow_path = $pipeline_params->nextflow_path;
        $nf_core_scgs_path = $pipeline_params->nf_core_scgs_path;
        $command = $nextflow_path . ' run '. $nf_core_scgs_path . ' ' . $current_job->command . ' -profile docker,base -name uuid-' . $current_job->current_uuid . ' -with-weblog '. env('WEBLOG_SERVER', 'http://localhost') .'/execute/start';
        $cmd_wrap = 'mkdir -p ' . $base_path . $project_accession . '/' . $job_uuid . ' && chmod -R 777 ' . $base_path . $project_accession . '/' . $job_uuid . ' && cd ' . $base_path . $project_accession . '/' . $job_uuid . ' && ' . $command;
        system($cmd_wrap);
    }

    public function failed()
    {
        $finished = time();
        $current_job_id = Jobs::where('sample_id', '=', $this->run_sample_id)->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->status = 2; //job failed
        $current_job->finished = $finished;  //finished time
        $current_job->save();

        $sample = Samples::find($this->run_sample_id);
        $sample->status = 2;  //job failed
        $sample->save();
    }
}
