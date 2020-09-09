<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Execparams extends Model
{
    //
    protected $table = 'execparams';
    protected $fillable = ['id', 'samples_id', 'ass', 'cnv', 'snv', 'genus', 'genus_name', 'resfinder_db', 'nt_db', 'kraken_db', 'eggnog', 'kofam_profile', 'kofam_kolist'];
}