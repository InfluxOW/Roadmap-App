<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseCompletionRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseCompletionsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Course::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'store' => 'complete',
            'update' => 'updateCompletion',
            'destroy' => 'incomplete',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return [];
    }

    public function store(Course $course, Request $request)
    {
        $request->user()->complete($course);

        return response(['message' => "Course has been marked as completed"], 200);
    }

    public function update(Course $course, CourseCompletionRequest $request)
    {
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
        $request->user()->incomplete($course);

        return response(['message' => "Course has been marked as incompleted"], 200);
    }
}
