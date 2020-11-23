<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Execparams extends Model
{
    //
    protected $table = 'execparams';
    protected $fillable = ['id', 'sample_id', 'project_id', 'ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db', 'eggnog', 'kofam_profile', 'kofam_kolist',  'augustus_species', 'augustus_species_name'];
}
