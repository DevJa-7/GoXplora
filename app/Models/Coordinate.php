<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    use CrudTrait;
    use SpatialTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'coordinates';
    protected $fillable = ['title', 'position', 'latitude', 'longitude', 'location', 'radius'];
    protected $hidden = ['pivot'];
    protected $spatialFields = [
        'position',
    ];

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

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_coordinate', 'coordinate_id', 'module_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getLocationAttribute()
    {
        return "{$this->position->getLat()}, {$this->position->getLng()}";
    }

    public function setLocationAttribute($value)
    {
        list($lat, $long) = explode(' ', trim($value, "\t\n\r \x0B()"));
        $this->position = new Point($lat, $long);

        $this->latitude = $this->position->getLat();
        $this->longitude = $this->position->getLng();
    }

    public function getDetailAttribute()
    {
        return "{$this->title} ({$this->latitude}, {$this->longitude})";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function toArray()
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
