<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cameras extends Model
{
    use CrudTrait,HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cameras';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $casts =[
        'lat_lang'=>"object",
    ];
     protected $guarded = [
         'id',
     ];
    // protected $hidden = [];
    // protected $dates = [];


    public function openStream($crud = false)
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="'.$this->url.'" data-toggle="tooltip" title="stream custom button."><i class="la la-camera"></i> Watch Stream</a>';
    }
    public function openNN($crud = false)
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="'.$this->nn_url.'" data-toggle="tooltip" title="stream custom button."><i class="las la-network-wired"></i> Open Neural Network</a>';
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
