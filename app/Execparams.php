<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Execparams extends Model
{
    //
    protected $table = 'execparams';
    protected $fillable = ['id', 'samples_id', 'project_id', 'execute_params'];
}
