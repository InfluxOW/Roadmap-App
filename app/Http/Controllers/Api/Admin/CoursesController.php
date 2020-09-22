<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Repositories\CoursesRepository;

class CoursesController extends Controller
{
    protected CoursesRepository $repository;

    public function __construct(CoursesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     * path="/admin/courses",
     * summary="Courses Store",
     * description="Store a new course",
     * operationId="coursesStore",
     * tags={"Courses"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Course information",
     *    @OA\JsonContent(
     *       required={"name", "source", "description", "level"},
     *       @OA\Property(property="name", type="string", example="Basic PHP course"),
     *       @OA\Property(property="source", type="string", example="http://best-php-course-ever.com"),
     *       @OA\Property(property="description", type="string", example="Best PHP course ever"),
     *       @OA\Property(property="level", type="string", example="junior"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Specified course has been stored",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Course",
     *    )
     *   )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     * ),
     * @OA\Response(
     *    response=403,
     *    description="Unauthorized",
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Course has not been stored due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="name",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The name attribute is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Http\Requests\CourseRequest $request
     * @return \App\Http\Resources\CourseResource
     */
    public function store(CourseRequest $request)
    {
        $course = $this->repository->store($request);

        return new CourseResource($course);
    }

    /**
     * @OA\Put(
     * path="/admin/courses/{course:slug}",
     * summary="Course Update",
     * description="Update course information",
     * operationId="coursesUpdate",
     * tags={"Courses"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="course:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Course information",
     *    @OA\JsonContent(
     *       required={"name", "source", "description", "level"},
     *       @OA\Property(property="name", type="string", example="Basic PHP course"),
     *       @OA\Property(property="source", type="string", example="http://best-php-course-ever.com"),
     *       @OA\Property(property="description", type="string", example="Best PHP course ever"),
     *       @OA\Property(property="level", type="string", example="junior"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Specified course has been updated",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Course",
     *    )
     *   )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     * ),
     * @OA\Response(
     *    response=403,
     *    description="Unauthorized",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="Specified course has not been found"
     *   ),
     * @OA\Response(
     *     response=422,
     *     description="Course has not been updated due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="name",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The name attribute is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Http\Requests\CourseRequest $request
     * @param \App\Models\Course $course
     * @return \App\Http\Resources\CourseResource
     */
    public function update(CourseRequest $request, Course $course)
    {
        $this->repository->update($request);

        return new CourseResource($course);
    }

    /**
     * @OA\Delete(
     * path="/admin/courses/{course:slug}",
     * summary="Course Destroy",
     * description="Delete a specific course",
     * operationId="coursesDestroy",
     * tags={"Courses"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="course:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Response(
     *    response=204,
     *    description="Specified course has been deleted",
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     * ),
     * @OA\Response(
     *    response=403,
     *    description="Unauthorized",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="Specified course has not been found"
     *   ),
     * )
     * @param \App\Models\Course $course
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->noContent();
    }
}
