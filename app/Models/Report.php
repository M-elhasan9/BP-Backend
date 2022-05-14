<?php

namespace App\Models;

use App\Http\Controllers\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Report extends Model
{
    use CrudTrait, HasFactory, HasApiTokens;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reports';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $casts = [
        'lat_lang' => "json",
    ];
    protected $fillable = [
        'id',
        'status',
        'den_degree',
        'lat_lang',
        'image',
        'created_at',
        'reporter_id',
        'reporter_type',
    ];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function reporter()
    {
        return $this->morphTo();
    }

    public function getFire()
{
    return $this->fire;
}
    public function fire()
    {
        return $this->belongsTo(Fire::class, );
    }
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
