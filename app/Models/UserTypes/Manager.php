<?php

namespace App\Models\UserTypes;

use App\Models\Preset;
use App\Models\Team;
use App\Models\User;
use Parental\HasParent;

class Manager extends User
{
    use HasParent;

    /*
     * Relations
     * */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function presets()
    {
        return $this->hasMany(Preset::class);
    }
}
