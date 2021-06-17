<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use App\Models\Traits\SaveMedia;

class Module extends Model
{
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;
    use HasTranslations;
    use SaveMedia;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'modules';
    protected $fillable = ['slug', 'type', 'reference', 'title', 'content', 'image', 'image_title', 'images', 'images360', 'videos', 'documents', 'models_3d', 'category_id', 'related', 'featured', 'ra_range', 'status', 'extras', 'extras_translatable', 'parent_id'];
    protected $fakeColumns = ['extras', 'extras_translatable'];
    protected $translatable = ['title', 'image_title', 'extras_translatable', 'content', 'images', 'images360', 'videos', 'audios', 'documents'];
    protected $casts = [
        'featured' => 'boolean',
        'ra_range' => 'boolean',
        'date' => 'date',
        'images' => 'array',
        'images360' => 'array',
        'videos' => 'array',
        'audios' => 'array',
        'documents' => 'array',
        'models_3d' => 'array',
        'extras_translatable' => 'array',
        'extras' => 'array',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_title',
            ],
        ];
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

    public function parent()
    {
        return $this->belongsTo('App\Models\Module', 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'module_tag', 'module_id', 'tag_id');
    }

    public function related()
    {
        return $this->belongsToMany('App\Models\Module', 'module_related', 'module_id', 'related_id');
    }

    public function children()
    {
        return $this->belongsToMany('App\Models\Module', 'module_children', 'module_id', 'child_id');
    }

    public function beacons()
    {
        return $this->belongsToMany('App\Models\Beacon', 'module_beacon', 'module_id', 'beacon_id');
    }

    public function coordinates()
    {
        return $this->belongsToMany('App\Models\Coordinate', 'module_coordinate', 'module_id', 'coordinate_id');
    }

    public function markers()
    {
        return $this->belongsToMany('App\Models\Marker', 'module_marker', 'module_id', 'marker_id');
    }

    public function routes()
    {
        return $this->belongsToMany('App\Models\Route', 'module_route', 'module_id', 'route_id');
    }

    public function questions()
    {
        return $this->belongsToMany('App\Models\SurveyQuestion', 'module_survey', 'module_id', 'survey_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('date', '<=', date('Y-m-d'))
            ->orderBy('date', 'DESC');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    // The slug is created automatically from the "title" field if no slug exists.
    public function getSlugOrTitleAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }

        return $this->title;
    }

    public function getFormattedDateSimpleAttribute()
    {
        return $this->date->format('d') . '/' . $this->date->format('m') . '/' . $this->date->format('Y');
    }

    public function getOrderNameAttribute()
    {
        return "$this->id (" . ucfirst(__($this->type)) . ") - $this->title";
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('d') . ' ' . __($this->date->format('F')) . ' ' . $this->date->format('Y');
    }

    public function getTitleTranslatedAttribute()
    {
        return $this->title;
    }

    public function getDetailAttribute()
    {
        return $this->title;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        $filename = $this->attributes['title'];
        $this->saveImage($this, $value, 'modules/', $filename, [1280, 384], 85);
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
