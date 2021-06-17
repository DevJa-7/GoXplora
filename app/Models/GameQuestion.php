<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class GameQuestion extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'game_questions';
    protected $fillable = ['title', 'module_id', 'images', 'option_a', 'option_b', 'option_c', 'option_d', 'correct'];
    protected $translatable = ['title', 'content', 'option_a', 'option_b', 'option_c', 'option_d'];
    protected $casts = [
        'images' => 'array',
        'correct' => 'int',
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

    public function answer()
    {
        return $this->hasMany('App\Models\GameAnswer');
    }

    public function module()
    {
        return $this->belongsTo('App\Models\Module', 'module_id');
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
