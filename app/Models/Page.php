<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use App\Models\Traits\SaveMedia;

class Page extends \Backpack\PageManager\app\Models\Page
{
    use HasTranslations;
    use SaveMedia;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $translatable = ['title', 'extras_translatable'];
    protected $fakeColumns = ['extras', 'extras_translatable'];
    protected $casts = ['texts' => 'array', 'translatable' => 'array'];

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
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setExtrasAttribute($value)
    {
        $value = json_decode($value);

        // Image
        if (isset($value->image)) {

            $result = $this->saveImage($this, $value->image, 'pages/', 'arraiolos', 1280, 90);

            $value->image = $result;

            unset($this->attributes['image']);
        }

        $this->attributes['extras'] = json_encode($value);
    }

    public function setExtrasTranslatableAttribute($value)
    {
        // Image
        if (isset($value['image'])) {

            $result = $this->saveImage($this, $value['image'], 'pages/', 'arraiolos', 1280, 90);

            $value['image'] = $result;

            unset($this->attributes['image']);
        }

        $this->attributes['extras_translatable'] = $value;
    }
}
