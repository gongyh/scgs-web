<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Execparams extends Model
{
    //
    protected $table = 'execparams';
    protected $fillable = ['id', 'samples_id', 'project_id', 'ass', 'cnv', 'snv', 'bulk', 'saturation', 'acquired', 'saveTrimmed', 'saveAlignedIntermediates', 'resume', 'euk', 'fungus', 'genus', 'genus_name', 'reference_genome', 'resfinder_db', 'nt_db', 'kraken_db', 'eggnog', 'kofam_profile', 'kofam_kolist', 'eukcc_db', 'augustus_species', 'augustus_species_name'];
}
