<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $guarded = [];
    protected $hidden = [];
    protected $fillable = ['active'];
    protected $fakeColumns = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function toggleActive($crud = false)
    {
        return
        '<div class="btn btn-xs btn-default on ' . ($this->active ? '' : 'hide') . '" ajax-toggle-id="' . $this->id . '" ajax-toggle="/admin/country/' . $this->id . '/0"><i class="fa fa-check-square-o"></i> ' . __('Deactivate') . '</div>' .
        '<div class="btn btn-xs btn-default off ' . ($this->active ? 'hide' : '') . '" ajax-toggle-id="' . $this->id . '" ajax-toggle="/admin/country/' . $this->id . '/1"><i class="fa fa-square-o"></i> ' . __('Activate') . '</div>';
    }
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
}
