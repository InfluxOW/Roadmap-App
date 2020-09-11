<?php

namespace App\Models\UserTypes;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Roadmap;
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

    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function presets()
    {
        return $this->hasMany(Preset::class, 'manager_id');
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'manager_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id')->withPivot('assigned_at');
    }
}
