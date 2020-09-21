<?php

namespace App\Models;

use App\Models\UserTypes\Admin;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Parental\HasChildren;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasChildren;
    use HasApiTokens;
    use HasRelationships;

    protected $childTypes = [
        'admin' => Admin::class,
        'manager' => Manager::class,
        'employee' => Employee::class
    ];
    protected $childColumn = 'role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'sex', 'birthday', 'position'
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
        'birthday' => 'datetime',
    ];

    /*
     * Role Checks
     * */

    public function isAdmin()
    {
        return $this instanceof Admin;
    }

    public function isManager()
    {
        return $this instanceof Manager;
    }

    public function isEmployee()
    {
        return $this instanceof Employee;
    }

    public function isNotAdmin()
    {
        return ! $this->isAdmin();
    }

    public function isNotManager()
    {
        return ! $this->isManager();
    }

    public function isNotEmployee()
    {
        return ! $this->isEmployee();
    }
}
