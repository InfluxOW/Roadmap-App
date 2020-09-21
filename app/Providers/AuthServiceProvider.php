<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Preset;
use App\Models\User;
use App\Policies\CoursesPolicy;
use App\Policies\PresetsPolicy;
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
