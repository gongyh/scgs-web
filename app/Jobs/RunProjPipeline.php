<?php

namespace App\Jobs;

use App\User;
use App\Jobs;
use App\Projects;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunProjPipeline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $run_project_id;
    public function __construct($run_project_id)
    {
        //
        $this->run_project_id = $run_project_id;
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
        if (Jobs::where('project_id', $this->run_project_id)->count() == 0 || Jobs::where('project_id', $this->run_project_id)->value('uuid') == 'default') {
            $job_uuid = $job_rawbody['uuid'];
        } else {
            $job_uuid = Jobs::where('project_id', $this->run_project_id)->value('uuid');
        }
        /**
         * Get current time -- job beginning time
         */
        $started = time();
        /**
         * Job table update
         */
        $current_job_id = $job->where([['project_id', '=', $this->run_project_id], ['status', '=', 0]])->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->uuid = $job_uuid;
        $current_job->current_uuid = $job_rawbody['uuid'];
        $current_job->started = $started;
        $current_job->status = 1; //Running
        $current_job->save();

        /**
         * Execute params
         */
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $project_id = $current_job->project_id;
        $project_accession = Projects::where('id', $project_id)->value('doi');
        $command = $current_job->command . ' -name uuid-' . $current_job->current_uuid . ' -with-weblog ' . env('WEBLOG_SERVER','http://localhost') . '/execute/start';
        $mkdir = 'if [ ! -d "' . $base_path . $project_accession . '/' . $job_uuid . '" ]; then mkdir -p ' . $base_path . $project_accession . '/' . $job_uuid . '; fi';
        $chmod = 'cd ' . $base_path . ' && sudo chown -R apache:apache ' . $project_accession . ' && sudo chmod -R 777 ' . $project_accession;
        $cd_and_command = 'cd ' . $base_path . $project_accession . '/' . $job_uuid . ' && ' . $command;
        system($mkdir);
        system($chmod);
        system($cd_and_command);
    }

    public function failed()
    {
        $finished = time();
        $current_job_id = Jobs::where('project_id', '=', $this->run_project_id)->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->status = 2; //job failed
        $current_job->finished = $finished;  //finished time
        $current_job->save();
    }
}
