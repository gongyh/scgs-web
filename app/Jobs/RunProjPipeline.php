<?php

namespace App\Jobs;

use App\User;
use App\Jobs;
use App\Projects;
use App\PipelineParams;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        $current_job->status = 1; // Running
        $current_job->save();

        /**
         * Execute params
         */
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $project_id = $current_job->project_id;
        $project_accession = Projects::where('id', $project_id)->value('doi');
        $workdir = $base_path . $project_accession . '/' . $job_uuid;
        system('mkdir -p ' . $workdir . ' && chmod -R 777 ' . $workdir);

        $pipeline_params = PipelineParams::find(1);
        $nextflow_path = $pipeline_params->nextflow_path;
        $nf_core_scgs_path = $pipeline_params->nf_core_scgs_path;
        $nextflow_profile = $pipeline_params->nextflow_profile;
        $profile_string = "docker,base";
        $nextflow_config = "";
        switch ($nextflow_profile) {
            case "Local":
                $profile_string = "docker,base";
                break;
            case "Kubernetes":
                $k8s_configf = $workdir . '/k8s.config';
                $profile_string = 'k8s,standard';
                $k8s_config = \fopen($k8s_configf, "w");
                $k8s_autoMountHostPaths = 'k8s.autoMountHostPaths = false' . PHP_EOL;
                $k8s_launchDir = 'k8s.launchDir = ' . '\'' . $workdir . '\'' . PHP_EOL;
                $k8s_workDir = 'k8s.workDir = \'' . $workdir . '/work\'' . PHP_EOL;
                $k8s_projectDir = 'k8s.projectDir = \'' . $base_path . 'projects\'' . PHP_EOL;
                $k8s_pod = 'k8s.pod = [[volumeClaim:"scgs-db-pvc", mountPath:"/mnt/share"],[imagePullPolicy:"IfNotPresent"],[nodeSelector:"kubernetes.io/hostname=gnode7"]]' . PHP_EOL;
                $k8s_storageClaimName = 'k8s.storageClaimName = \'scgs-data-pvc\'' . PHP_EOL;
                $k8s_storageMountPath = 'k8s.storageMountPath = \'' . rtrim($base_path,'/') . '\'' . PHP_EOL;
                $k8s_namespace = "k8s.namespace = 'school'" . PHP_EOL;
                $k8s_txt = $k8s_autoMountHostPaths . $k8s_launchDir . $k8s_workDir . $k8s_projectDir . $k8s_pod . $k8s_storageClaimName . $k8s_storageMountPath . $k8s_namespace;
                \fwrite($k8s_config, $k8s_txt);
                \fclose($k8s_config);
                $nextflow_config = " -c " . $k8s_configf;
                break;
            default:
                $profile_string = "docker,base";
                break;
        }
        $command = $nextflow_path . $nextflow_config . ' run '. $nf_core_scgs_path . ' ' . $current_job->command . ' -profile ' . $profile_string . ' -name uuid-' . $current_job->current_uuid . ' -with-weblog '. env('WEBLOG_SERVER', 'http://localhost') .'/execute/start';
        // dump($command);
        $cmd_wrap = 'cd ' . $workdir . ' && ' . $command;
        Log::debug($cmd_wrap);
        system($cmd_wrap);
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
