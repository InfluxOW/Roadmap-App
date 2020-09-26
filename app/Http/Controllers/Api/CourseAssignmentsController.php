<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAssignmentRequest;
use App\Models\Course;
use App\Models\Preset;
use Illuminate\Http\Request;

class CourseAssignmentsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Preset::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'store' => 'update',
            'destroy' => 'update',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return [];
    }

    public function store(Preset $preset, CourseAssignmentRequest $request)
    {
        $preset->courses()->attach(
            Course::whereSlug($request->course)->first(),
            ['assigned_at' => now()]
        );

        return response(['message' => 'Specified course has been assigned to the preset.'], 200);
    }

    public function destroy(Preset $preset, Course $course)
    {
        $preset->courses()->detach($course);

        return response(['message' => 'Specified course has been unassigned from the preset.'], 200);
    }
}