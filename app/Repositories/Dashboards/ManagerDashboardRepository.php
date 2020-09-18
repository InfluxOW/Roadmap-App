<?php

namespace App\Repositories\Dashboards;

use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagerDashboardRepository
{
    private const WITH = [
        'employees.roadmaps.preset.courses.completions',
        'employees.roadmaps.preset.courses.level',
        'employees.roadmaps.preset.courses.technologies',
        'employees.roadmaps.preset.manager',
        'employees.roadmaps.employee',
    ];

    public function index(Request $request)
    {
        return Manager::whereUsername($request->route('manager'))->with(self::WITH)->firstOrFail();
    }
}
