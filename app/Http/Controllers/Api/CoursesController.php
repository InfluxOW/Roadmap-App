<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\SuggestCourseRequest;
use App\Http\Resources\CoursesResource;
use App\Jobs\ProcessSuggestedCourse;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CoursesRepository;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    protected CoursesRepository $repository;

    public function __construct(CoursesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $this->authorize(Course::class);

        $courses = $this->repository->index($request);

        return CoursesResource::collection($courses);
    }

    public function show(Request $request)
    {
        $this->authorize(Course::class);

        $course = $this->repository->show($request);

        return new CoursesResource($course);
    }

    public function suggest(SuggestCourseRequest $request)
    {
        $this->authorize('suggestCourse', User::class);

        ProcessSuggestedCourse::dispatch($request->source);

        return response(['message' => 'Course has been suggested. Please, wait until we process it.'], 200);
    }
}
