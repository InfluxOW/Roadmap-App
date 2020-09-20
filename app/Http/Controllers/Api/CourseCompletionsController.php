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
        $this->authorize('complete', $course);

        $request->user()->complete($course);

        return response(['message' => "Course has been marked as completed"], 200);
    }

    public function update(Course $course, CourseCompletionRequest $request)
    {
        $this->authorize('updateCompletion', $course);

        if ($request->rating) {
            $request->user()->rate($course, $request->rating);
        }

        if ($request->certificate) {
            $request->user()->attachCertificateTo($course, $request->certificate);
        }

        return response(['message' => "Course completion information has been updated"], 200);
    }

    public function destroy(Course $course, Request $request)
    {
        $this->authorize('incomplete', $course);

        $request->user()->incomplete($course);

        return response(['message' => "Course has been marked as incompleted"], 200);
    }
}
