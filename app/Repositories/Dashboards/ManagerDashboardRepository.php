<?php

namespace App\Repositories\Dashboards;

use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagerDashboardRepository
{
    private const WITH = [
        'ownedTeams.employees.roadmaps.preset.courses.completions',
        'ownedTeams.employees.roadmaps.preset.courses.level',
        'ownedTeams.employees.roadmaps.preset.courses.technologies',
        'ownedTeams.employees.roadmaps.preset.manager',
        'ownedTeams.employees.roadmaps.employee.company',
        'ownedTeams.employees.company',
        'ownedTeams.employees.teams',
        'teams.employees.roadmaps.preset.courses.completions',
        'teams.employees.roadmaps.preset.courses.level',
        'teams.employees.roadmaps.preset.courses.technologies',
        'teams.employees.roadmaps.preset.manager',
        'teams.employees.roadmaps.employee.company',
        'teams.employees.company',
        'teams.employees.teams'
    ];

    public function index(Request $request)
    {
        return Manager::whereUsername($request->route('manager'))->with(self::WITH)->firstOrFail();
    }
}
