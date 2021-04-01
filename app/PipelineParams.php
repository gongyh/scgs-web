<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PipelineParams extends Model
{
    //
    protected $table = 'pipeline_params';
    protected $fillable = ['id', 'resfinder_db_path', 'nt_db_path', 'kraken_db_path', 'eggnog_db_path', 'kofam_profile_path', 'kofam_kolist_path','eukcc_db_path','nextflow_path','nf_core_scgs_path','nextflow_config'];
}
