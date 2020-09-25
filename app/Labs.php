<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Peojects;

class Labs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'labs';
    protected $fillable = ['id', 'name', 'principleInvestigator', 'institutions_id'];
}
