<?php

namespace App;

use App\Labs;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';
    protected $fillable = ['labs_id', 'name', 'description', 'doi','type','collection_date','location'];
    public function getLabName($id)
    {
        $lab_name = Labs::where('id', $id)->value('name');
        return $lab_name;
    }
}
