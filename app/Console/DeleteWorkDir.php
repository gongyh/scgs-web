<?php

use App\Jobs;
use App\Samples;
use App\Projects;
use Illuminate\Support\Facades\Storage;

class DeleteWorkDir
{
    protected function __invoke()
    {
        $now = time();
        $jobs = Jobs::where('status', 3)->get();
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        foreach ($jobs as $job)
        {
            if ($now - $job->finished > 7776000)
            {
                if (isset($job->project_id))
                {
                    $accession = Projects::where('id', $job->project_id)->value('doi');
                    $uuid = $job->uuid;
                    $dir = $base_path . $accession . '/' . $uuid .'/work';
                    if (Storage::disk('local')->exists($dir))
                    {
                        $delete_workdir_cmd = 'rm -rf ' . $dir;
                        system($delete_workdir_cmd);
                    }
                }
                else
                {
                    $sample_id = $job->sample_id;
                    $project_id = Samples::where('id', $sample_id)->value('projects_id');
                    $accession = Projects::where('id', $project_id)->value('doi');
                    $uuid = $job->uuid;
                    $dir = $base_path . $accession . '/' . $uuid . '/work';
                    if (Storage::disk('local')->exists($dir))
                    {
                        $delete_workdir_cmd = 'rm -rf ' . $dir;
                        system($delete_workdir_cmd);
                    }
                }
            }
        }
    }
}
