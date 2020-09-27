<?php

namespace App\Providers;

use App\Jobs;
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
            $current_job_id = Jobs::where('status', 1)->value('id');
            $current_job = Jobs::find($current_job_id);
            $finished = time();
            $current_job->status = 3;   //任务完成
            $current_job->finished = $finished;  //任务完成时间
            $current_job->save();
        });
    }
}
