<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\Concerns\Has;

class Fire extends Model
{
    use CrudTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'fires';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = [
        'lat_lang' => "object",
    ];

    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function sendNotify($crud = false)
    {

            $btn =  "<button class='btn btn-danger btn-sm '>
                        <a class='nav-link p-0' style='color: #fff' href='/admin/sendFireNotifyToNearbyUser/$this->id'>Send Notify</a>
                    </button>";
        return "<span class='mx-5' style='width: 50px;display: inline-block'>$btn</span>";

    }

    public function getFiredegreeAttribute()
    {
        switch ($this->den_degree) {
            case 1:
                return "Fake";
            case 2:
                return "Low";
            case 3:
                return "Normal";
            case 4:
                return "High";
            case 5:
                return "dangerous";
            default:
                return "No Degree";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function reports()
    {
        return $this->hasMany(Report::class, 'fire_id');
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
