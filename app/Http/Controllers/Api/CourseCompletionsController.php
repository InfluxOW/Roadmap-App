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

    /**
     * @OA\Post(
     * path="/courses/{course:slug}/completions",
     * summary="Course Completions Store",
     * description="Complete a specific course",
     * operationId="courseCompletionsStore",
     * tags={"Course Completions"},
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
     *    description="Specified course has been completed",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Course has been marked as completed"),
     *    )
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Course $course, Request $request)
    {
        $request->user()->complete($course);

        return response(['message' => "Course has been marked as completed"], 200);
    }

    /**
     * @OA\Put(
     * path="/courses/{course:slug}/completions",
     * summary="Course Completions Update",
     * description="Rate a course or attach a certificate to it",
     * operationId="courseCompletionsUpdate",
     * tags={"Course Completions"},
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
     *    description="Rating or/and certificate",
     *    @OA\JsonContent(
     *       @OA\Property(property="rating", type="integer", example=5),
     *       @OA\Property(property="certificate", type="string", example="http://test.com/certificate.jpg"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Specified course completion information has been updated",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Course completion information has been updated"),
     *    )
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
     *     description="Course completion information has not been updated due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="rating",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The rating attribute field does not exist in [0,1,2,3,4,5,6,7,8,9,10]."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Models\Course $course
     * @param \App\Http\Requests\CourseCompletionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @OA\Delete(
     * path="/courses/{course:slug}/completions",
     * summary="Course Completions Destroy",
     * description="Incomplete a specific course",
     * operationId="courseCompletionsDestroy",
     * tags={"Course Completions"},
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
     *    description="Specified course has been incompleted",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Course has been marked as incompleted"),
     *    )
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Course $course, Request $request)
    {
        $request->user()->incomplete($course);

        return response(['message' => "Course has been marked as incompleted"], 200);
    }
}
