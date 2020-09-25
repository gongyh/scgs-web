<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    //
    protected $table = 'jobs';
    protected $fillable = ['id', 'uuid', 'sample_id', 'user_id', 'status', 'command'];
}
