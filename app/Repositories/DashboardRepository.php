<?php

namespace App\Repositories;

use App\Models\UserTypes\Employee;
use Illuminate\Http\Request;

class DashboardRepository
{
    private const WITH = [
        'roadmaps.preset.courses.completions.employee',
        'roadmaps.preset.courses.level',
        'roadmaps.preset.courses.manager',
        'roadmaps.preset.manager',
        'roadmaps.preset.roadmaps.employee.company'
    ];

    public function employee(Request $request)
    {
        $employee = Employee::whereUsername($request->route('employee'))->with(self::WITH)->firstOrFail();

        $request->request->add(['employee' => $employee]); // some kind of caching

        return $employee;
    }
}
