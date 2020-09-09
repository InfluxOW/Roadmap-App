<?php

namespace App\Models;

use App\Models\Users\Admin;
use App\Models\Users\Employee;
use App\Models\Users\Manager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Parental\HasChildren;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasChildren;

    protected $childTypes = [
        'admin' => Admin::class,
        'manager' => Manager::class,
        'employee' => Employee::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
