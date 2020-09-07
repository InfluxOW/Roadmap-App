<?php

namespace App\Models;

use App\Models\Users\Admin;
use App\Models\Users\Developer;
use App\Models\Users\Manager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Parental\HasChildren;

abstract class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasChildren;

    protected $childTypes = [
        'admin' => Admin::class,
        'manager' => Manager::class,
        'developer' => Developer::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
