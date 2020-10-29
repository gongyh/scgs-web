<?php

namespace App\Providers;

use App\Jobs;
use App\User;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Queue;
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

        Queue::after(function () {
            /**
             * 压缩results.zip
             */
            $sample_id = Jobs::where('status', 1)->value('sample_id');
            $base_path =  Storage::disk('local')->getAdapter()->getPathPrefix();
            $user_id = Jobs::where('sample_id', $sample_id)->value('user_id');
            $sample_username = User::where('id', $user_id)->value('name');
            $uuid = Jobs::where('sample_id', $sample_id)->value('uuid');
            $zip_full_name = $base_path . $sample_username . '/' . $uuid . '/' . $sample_username . '_' . $uuid . '_results.zip';
            $zip = new ZipArchive();
            $path = $base_path . $sample_username . '/' . $uuid . '/results';
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
            if ($zip->open($zip_full_name, ZipArchive::CREATE  | ZipArchive::OVERWRITE) == true) {
                addFileToZip($path, $zip);
                $zip->close();
            }

            /**
             * 修改运行job status
             */
            $current_job_id = Jobs::where('status', 1)->value('id');
            $current_job = Jobs::find($current_job_id);
            $finished = time();
            $current_job->status = 3;   //任务完成
            $current_job->finished = $finished;  //任务完成时间
            $current_job->save();
        });
    }
}
