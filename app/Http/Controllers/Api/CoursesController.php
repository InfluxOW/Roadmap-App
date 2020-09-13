<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CoursesResource;
use App\Models\Course;
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

    public function store(CourseRequest $request)
    {
        $this->authorize(Course::class);

        $course = $this->repository->store($request);

        return redirect()->route('courses.show', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $this->authorize($course);

        $course->update($request->validated());

        return redirect()->route('courses.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        $this->authorize($course);

        $course->delete();

        return redirect()->route('courses.index');
    }
}
