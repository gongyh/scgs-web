<?php

namespace App\Providers;

use App\Jobs;
use App\User;
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
            $job_uuid = $event->job->uuid();
            $sample_id = Jobs::where('current_uuid', $job_uuid)->value('sample_id');
            $project_id = Jobs::where('current_uuid', $job_uuid)->value('project_id');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            isset($sample_id) ? $user_id = Jobs::where('sample_id', $sample_id)->value('user_id') : $user_id = Jobs::where('project_id', $project_id)->value('user_id');
            $sample_username = User::where('id', $user_id)->value('name');
            isset($sample_id) ? $uuid = Jobs::where('sample_id', $sample_id)->value('uuid') : $uuid = Jobs::where('project_id', $project_id)->value('uuid');
            $zip_full_name = $base_path . $sample_username . '/' . $uuid . '/results.zip';
            $path = $base_path . $sample_username . '/' . $uuid . '/results';
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
        });

    }
}
