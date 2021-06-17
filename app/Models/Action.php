<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use CrudTrait;

    protected $table = 'actions';
    protected $fillable = ['user_id', 'type', 'detail'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
