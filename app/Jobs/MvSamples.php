<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Projects;
use App\Samples;
use App\Labs;


class MvSamples implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $project_id;
    private $filename1;
    private $filename2;

    public function __construct($project_id, $filename1, $filename2)
    {
        //
        $this->project_id = $project_id;
        $this->filename1 = $filename1;
        $this->filename2 = $filename2;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $base_path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $Accession = Projects::where('id', $this->project_id)->value('doi');
        $sample_id = Samples::where('filename1', $this->filename1)->value('id');
        $lab_id = Projects::where('id', $this->project_id)->value('labs_id');
        $user = Labs::where('id', $lab_id)->value('principleInvestigator');
        $sample = Samples::find($sample_id);
        $mk_project_dir = 'if [ ! -d "' . $base_path . $Accession . '" ]; then mkdir -p ' . $base_path . $Accession . '; fi';
        if($this->filename2 == null){
            $cp_sample_file = 'cp ' . $base_path . 'meta-data/' . $user . '/' . $this->filename2 . ' ' . $base_path . $Accession;
        }else{
            $cp_sample_file = 'cp ' . $base_path . 'meta-data/' . $user . '/' . $this->filename1 . ' ' . $base_path . $Accession . ' && cp ' . $base_path . 'meta-data/' . $user . '/' . $this->filename2 . ' ' . $base_path . $Accession;
        }
        echo($mk_project_dir);
        echo($cp_sample_file);
        $sample->isPrepared = 1;  //Prepared : 1
        $sample->save();
    }
}
