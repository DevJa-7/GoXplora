<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'survey_questions';
    protected $fillable = ['title', 'options', 'type'];
    protected $translatable = ['title', 'options'];
    protected $casts = ['options' => 'array'];

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

    public function answer()
    {
        return $this->hasMany('App\Models\SurveyAnswer');
    }

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_survey', 'survey_id', 'module_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getDetailAttribute()
    {
        return "$this->title";
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['detail'] = $this->detail;
        return $data;
    }
}
