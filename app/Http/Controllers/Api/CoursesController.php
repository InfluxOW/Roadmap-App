<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuggestCourseRequest;
use App\Http\Resources\CourseResource;
use App\Jobs\ProcessSuggestedCourse;
use App\Models\Course;
use App\Repositories\CoursesRepository;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    protected CoursesRepository $repository;

    public function __construct(CoursesRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(Course::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'suggest' => 'suggest',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'show', 'suggest'];
    }

    /**
     * @OA\Get(
     * path="/courses",
     * summary="Courses Index",
     * description="View all courses",
     * operationId="coursesIndex",
     * tags={"Courses"},
     * security={
     *   {"access_token": {}},
     * },
     *  @OA\Parameter(
     *    name="filter[name]",
     *    in="query",
     *    description="Filter courses by specific name",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[source]",
     *    in="query",
     *    description="Filter courses by specific source",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[levels]",
     *    in="query",
     *    description="Filter courses by specific skill levels",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[technologies]",
     *    in="query",
     *    description="Filter courses by specific technologies",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[presets]",
     *    in="query",
     *    description="Filter courses by specific presets",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[completed_by]",
     *    in="query",
     *    description="Filter courses by completion by specific employees",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort courses by one of the available params: name, source or level. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="page",
     *    in="query",
     *    description="Results page",
     *    required=false,
     *    @OA\Schema(
     *         type="integer"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="per",
     *    in="query",
     *    description="Results per page",
     *    required=false,
     *    @OA\Schema(
     *         type="integer"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Courses were fetched",
     *     @OA\JsonContent(
     *     @OA\Property(
     *      property="courses",
     *      type="object",
     *      collectionFormat="multi",
     *       @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/CourseResource",
     *        )
     *      ),
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
     * )
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $courses = $this->repository->index($request);

        return CourseResource::collection($courses);
    }

    /**
     * @OA\Get(
     * path="/courses/{course:slug}",
     * summary="Courses Show",
     * description="View a specific course",
     * operationId="coursesShow",
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
     *    response=200,
     *    description="Specified course has been fetched",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/CourseResource",
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
     * )
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\CourseResource
     */
    public function show(Request $request)
    {
        $course = $this->repository->show($request);

        return new CourseResource($course);
    }

    /**
     * @OA\Post(
     * path="/courses/suggestions",
     * summary="Courses Suggest",
     * description="Suggest a new course",
     * operationId="coursesSuggest",
     * tags={"Courses"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Course source",
     *    @OA\JsonContent(
     *       required={"source"},
     *       @OA\Property(property="source", type="string", example="http://test-course.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful suggestion",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Course has been suggested. Please, wait until we process it."),
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     * ),
     * )
     * @param \App\Http\Requests\SuggestCourseRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function suggest(SuggestCourseRequest $request)
    {
        ProcessSuggestedCourse::dispatch($request->source);

        return response(['message' => 'Course has been suggested. Please, wait until we process it.'], 200);
    }
}
