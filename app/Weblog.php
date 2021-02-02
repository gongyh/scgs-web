<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weblog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weblog';
    protected $fillable = ['runName','runId','event','utcTime','process'];
}
