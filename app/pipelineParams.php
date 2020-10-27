<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pipelineParams extends Model
{
    //
    protected $table = 'pipeline_params';
    protected $fillable = ['id', 'resfinder_db_path', 'nt_db_path', 'kraken_db_path', 'eggnog_db_path', 'kofam_profile_path', 'kofam_kolist_path', 'funannotate_db_path'];
}
