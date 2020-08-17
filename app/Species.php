<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'species';
    protected $fillable = ['id', 'name', 'fasta', 'gff'];
}
