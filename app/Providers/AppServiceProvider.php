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

    public function addFileToZip($path, $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                if (is_dir($path . '/' . $filename)) {
                    addFileToZip($path . '/' . $filename, $zip);
                } elseif (is_file($path . '/' . $filename)) {
                    $results_position = strpos($path, 'results');
                    $relative_path = substr($path, $results_position);
                    $zip->addFile($path . '/' . $filename, $relative_path . '/' . $filename);
                }
            }
        }
        @closedir($path);
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
            $zip_full_name = $base_path . $sample_username . '/' . $uuid . '/' . $sample_username . '_' . $uuid . '_results.zip';
            $zip = new ZipArchive();
            $path = $base_path . $sample_username . '/' . $uuid . '/results';
            if ($zip->open($zip_full_name, ZipArchive::CREATE  | ZipArchive::OVERWRITE) == true) {
                addFileToZip($path, $zip);
                $zip->close();
            }
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
