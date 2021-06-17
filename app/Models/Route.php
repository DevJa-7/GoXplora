<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SaveMedia;

class Route extends Model
{
    use CrudTrait;
    use HasTranslations;
    use SaveMedia;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'routes';
    protected $fillable = ['title', 'content', 'image', 'bounding_top', 'bounding_right', 'bounding_bottom', 'bounding_left'];
    public $translatable = ['title', 'content'];

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
        return $this->belongsToMany('App\Models\Module', 'module_route', 'route_id', 'module_id');
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

    public function setImageAttribute($value)
    {
        $filename = $this->attributes['title'];
        $this->saveImage($this, $value, 'routes/', $filename, [800, 256], 90);
    }

    public function getDetailAttribute()
    {
        return "$this->title";
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
