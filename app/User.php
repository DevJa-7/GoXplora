<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements AuthenticatableContract, CanResetPasswordContract
{
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'country', 'gender', 'phone', 'birth_date', 'terms',
        'api_token', 'data',
    ];

    protected $casts = [
        'data' => 'array',
        'terms' => 'array',
        'guest' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // /**
    //  * Send the password reset notification.
    //  *
    //  * @param  string  $token
    //  * @return void
    //  */
    // public function sendPasswordResetNotification($token)
    // {
    //     $this->notify(new ResetPasswordNotification($token));
    // }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\Models\Module', 'users_modules', 'user_id', 'module_id');
    }

    public function visited()
    {
        return $this->belongsToMany('App\Models\Module', 'users_visited_modules', 'user_id', 'module_id');
    }

    public function ranking()
    {
        return $this->hasOne('App\Models\GameRanking');
    }
}
