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
    protected $fillable = [
        'id', 'pairends', 'filename1', 'filename2', 'sampleLabel', 'species_id',
        'applications_id', 'projects_id'
    ];
}
