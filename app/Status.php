<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status';
    protected $fillable = ['id', 'user_id', 'sample_id', 'started', 'finished', 'status'];
}
