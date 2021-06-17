<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Beacon extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

    protected $guarded = [];
    protected $hidden = ['pivot'];
    protected $fillable = ['minor', 'title', 'reference', 'description', 'range', 'local', 'battery'];
    public $timestamps = true;
    public $translatable = ['title', 'description'];

    /*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

    public function toggleActive($crud = false)
    {
        return
        '<div class="btn btn-xs btn-default on ' . ($this->active ? '' : 'hide') . '" ajax-toggle-id="' . $this->id . '" ajax-toggle="/admin/beacon/' . $this->id . '/0"><i class="nav-icon la la-check-square-o"></i> Deactivate</div>' .
        '<div class="btn btn-xs btn-default off ' . ($this->active ? 'hide' : '') . '" ajax-toggle-id="' . $this->id . '" ajax-toggle="/admin/beacon/' . $this->id . '/1"><i class="nav-icon la la-square-o"></i> Activate</div>';
    }

    /*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_beacon', 'beacon_id', 'module_id');
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

    public function getDetailAttribute()
    {
        return "$this->title ($this->minor, " . __($this->local) . ')';
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
