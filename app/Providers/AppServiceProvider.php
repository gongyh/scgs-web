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
             * 压缩results.zip
             */
            $job_uuid = $event->job->uuid();
            $sample_id = Jobs::where('current_uuid', $job_uuid)->value('sample_id');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
            $sample_username = User::where('id', $user_id)->value('name');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
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

            $multiqc_mkdir = 'cd ' . public_path() . '/results && sudo mkdir -p ' . $sample_username . '/' . $uuid;
            $cp_multiqc = 'if [ -d ' . $path . '/MultiQC ]; then sudo cp -r ' . $path . '/MultiQC ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
            $cp_kraken = 'if [ -d ' . $path . '/kraken ]; then sudo cp -r ' . $path . '/kraken ' . public_path() . '/results/' . $sample_username . '/' . $uuid . '; fi';
            system($multiqc_mkdir);
            system($cp_multiqc);
            system($cp_kraken);
        });

        function addFileToZip($path, $zip)
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
    }
}
