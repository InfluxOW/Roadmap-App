<?php

namespace App\Repositories\Dashboards;

use App\Models\UserTypes\Employee;
use Illuminate\Http\Request;

class EmployeeDashboardRepository
{
    private const WITH = [
        'roadmaps.preset.courses.completions.employee',
        'roadmaps.preset.courses.level',
        'roadmaps.preset.courses.technologies',
        'roadmaps.preset.manager',
        'roadmaps.preset.roadmaps.employee.company',
        'roadmaps.employee'
    ];

    public function index(Request $request)
    {
        return Employee::whereUsername($request->route('employee'))->with(self::WITH)->firstOrFail();
    }
}
