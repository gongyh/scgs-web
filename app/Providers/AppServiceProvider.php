<?php

namespace App\Providers;

use App\Jobs;
use App\User;
use App\Samples;
use App\Projects;
use App\Ncbiupload;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Queue::after(function (JobProcessed $event) {
            /**
             * results.zip
             */
            if($event->job->getQueue() == "default"){
                $job_uuid = $event->job->uuid();
                $sample_id = Jobs::where('current_uuid', $job_uuid)->value('sample_id');
                $project_id = Jobs::where('current_uuid', $job_uuid)->value('project_id');
                $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
                isset($sample_id) ? $uuid = Jobs::where('sample_id', $sample_id)->value('uuid') : $uuid = Jobs::where('project_id', $project_id)->value('uuid');
                if(isset($sample_id)){
                    $projectId = Samples::where('id',$sample_id)->value('projects_id');
                    $project_accession = Projects::where('id',$projectId)->value('doi');
                }else{
                    $project_accession = Projects::where('id',$project_id)->value('doi');
                }
                $zip_full_name = $base_path . $project_accession . '/' . $uuid . '/results.zip';
                $path = $base_path . $project_accession . '/' . $uuid . '/results';
                $zipFile = new \PhpZip\ZipFile();
                $zipFile->addDirRecursive($path);
                $zipFile->saveAsFile($zip_full_name);
                $zipFile->close();

                /**
                 * change job status
                 */
                $current_job_id = Jobs::where('current_uuid', $job_uuid)->value('id');
                $current_job = Jobs::find($current_job_id);
                $finished = time();
                $current_job->status = 3;   //job finished
                $current_job->finished = $finished;  //job finished time
                $current_job->save();
            }elseif($event->job->getQueue() == 'NCBIDownload'){
                $job_uuid = $event->job->uuid();
                $upload_sra_id = Ncbiupload::where('uuid', $job_uuid)->value('id');
                $upload_sra = Ncbiupload::find($upload_sra_id);
                $upload_sra->isPrepared = true;
                $upload_sra->save();
            }
        });

    }
}
