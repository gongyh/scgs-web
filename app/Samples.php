<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Samples extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'samples';
    protected $fillable = ['sampleLabel', 'pairends'];
}
