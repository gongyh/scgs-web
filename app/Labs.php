<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Labs';
    protected $fillable = ['name', 'id', 'principleInvestigator', 'institution_id'];
}
