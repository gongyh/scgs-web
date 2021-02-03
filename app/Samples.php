<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Applications;
use App\Species;

class Samples extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'samples';
    protected $fillable = [
        'id', 'pairends', 'filename1', 'filename2', 'sampleLabel', 'library_id', 'library_strategy', 'library_source', 'library_selection', 'platform', 'instrument_model', 'design_description', 'filetype', 'species_id',
        'applications_id', 'projects_id','isPrepared','status'
    ];

    public function getAppName($id)
    {
        $app_name = Applications::where('id', $id)->value('name');
        return $app_name;
    }

    public function getSpeciesName($id)
    {
        $species_name = Species::where('id', $id)->value('name');
        return $species_name;
    }
}
