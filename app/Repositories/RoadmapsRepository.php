<?php

namespace App\Repositories;

use App\Models\Preset;
use App\Models\UserTypes\Employee;
use Illuminate\Http\Request;

class RoadmapsRepository
{
    public function store(Request $request)
    {
        $employee = Employee::whereUsername($request->employee)->firstOrFail();
        $preset = Preset::whereSlug($request->preset)->firstOrFail();

        return $request->user()->createRoadmap($preset, $employee);
    }

    public function destroy(Request $request)
    {
        $employee = Employee::whereUsername($request->route('employee'))->firstOrFail();
        $preset = Preset::whereSlug($request->route('preset'))->firstOrFail();

        return $request->user()->deleteRoadmap($preset, $employee);
    }
}
