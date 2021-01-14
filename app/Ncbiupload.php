<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ncbiupload extends Model
{
    //
    protected $table = 'ncbiupload';
    protected $fillable = ['id', 'sra_id', 'isPrepared', 'uuid', 'user'];
}
