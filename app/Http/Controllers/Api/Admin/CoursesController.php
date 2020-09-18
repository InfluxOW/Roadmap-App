<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CoursesResource;
use App\Models\Course;

class CoursesController extends Controller
{
    public function store(CourseRequest $request)
    {
        $course = Course::create($request->validated());

        return new CoursesResource($course);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return new CoursesResource($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->noContent();
    }
}