<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as MModel;

class Spectra extends MModel
{
    protected $connection = 'mongodb';
    protected $collection = 'test';

    protected $primaryKey = 'ID_Cell';
}
