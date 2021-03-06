<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'applications';
    protected $fillable = ['id', 'name', 'description'];
}
