<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseCompletionRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseCompletionsController extends Controller
{
    public function store(Course $course, Request $request)
    {
        $employee = $request->user();
        $this->authorize('manageCompletions', $employee);

        $employee->complete($course);

        return response(['message' => "Course has been marked as completed"], 200);
    }

    public function update(Course $course, CourseCompletionRequest $request)
    {
        $employee = $request->user();
        $this->authorize('manageCompletions', $employee);

        if ($request->rating) {
            $employee->rate($course, $request->rating);
        }

        if ($request->certificate) {
            $employee->attachCertificateTo($course, $request->certificate);
        }

        return response(['message' => "Course completion information has been updated"], 200);
    }

    public function destroy(Course $course, Request $request)
    {
        $employee = $request->user();
        $this->authorize('manageCompletions', $employee);

        $employee->incomplete($course);

        return response(['message' => "Course has been marked as incompleted"], 200);
    }
}
