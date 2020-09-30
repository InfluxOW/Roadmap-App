<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Course;
use App\Models\Invite;
use App\Models\Preset;
use App\Models\Team;
use App\Models\Technology;
use App\Models\User;
use App\Policies\CompaniesPolicy;
use App\Policies\CoursesPolicy;
use App\Policies\InvitesPolicy;
use App\Policies\PresetsPolicy;
use App\Policies\TeamsPolicy;
use App\Policies\TechnologiesPolicy;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Course::class => CoursesPolicy::class,
        Preset::class => PresetsPolicy::class,
        User::class => UsersPolicy::class,
        Technology::class => TechnologiesPolicy::class,
        Invite::class => InvitesPolicy::class,
        Company::class => CompaniesPolicy::class,
        Team::class => TeamsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
