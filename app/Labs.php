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
    protected $table = 'Labs';
    protected $fillable = ['name', 'id', 'principleInvestigator', 'institutions_id'];

    public function projects()
    {
        return $this->hasMany(Projects::class);
    }
}
