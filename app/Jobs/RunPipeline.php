<?php

namespace App\Jobs;


use App\Jobs;
use App\User;
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
         * job表记录更新
         */
        $current_job_id = $job->where([['sample_id', '=', $this->run_sample_id], ['status', '=', 0]])->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->uuid = $job_uuid;
        $current_job->current_uuid = $job_rawbody['uuid'];
        $current_job->started = $started;
        $current_job->status = 1; //正在运行
        $current_job->save();

        /**
         * 执行命令
         */
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $sample_user_id = $current_job->user_id;
        $sample_user_name = User::where('id', $sample_user_id)->value('name');
        $command = $current_job->command;
        $mkdir = 'if [ ! -d "' . $base_path . $sample_user_name . '/' . $job_uuid . '" ]; then mkdir -p ' . $base_path . $sample_user_name . '/' . $job_uuid . '; fi';
        $chmod = 'cd ' . $base_path . ' && sudo chown -R apache:apache ' . $sample_user_name . ' && sudo chmod -R 777 ' . $sample_user_name;
        $cd_and_command = 'cd ' . $base_path . $sample_user_name . '/' . $job_uuid . ' && ' . $command;
        system($mkdir);
        system($chmod);
        system($cd_and_command);
    }

    public function failed()
    {
        $finished = time();
        $current_job_id = Jobs::where('sample_id', '=', $this->run_sample_id)->value('id');
        $current_job = Jobs::find($current_job_id);
        $current_job->status = 2; //任务失败
        $current_job->finished = $finished;  //结束时间
        $current_job->save();
    }
}
