<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Repositories\CoursesRepository;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    protected CoursesRepository $repository;

    public function __construct(CoursesRepository $repository)
    {
        $this->authorizeResource(Course::class);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $courses = $this->repository->index($request);

        return CourseResource::collection($courses);
    }

    public function show(Course $course, Request $request)
    {
        return new CourseResource($course);
    }

    public function store(CourseRequest $request)
    {
        $course = $request->user()->isAdmin() ?
            Course::create($request->validated()) :
            $request->user()->courses()->create($request->validated());

        return redirect()->route('courses.show', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return redirect()->route('courses.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index');
    }
}
