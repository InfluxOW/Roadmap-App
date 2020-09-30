<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAssignmentRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Preset;
use Illuminate\Http\Request;

class PresetCoursesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Preset::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'store' => 'update',
            'destroy' => 'update',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return [];
    }

    /**
     * @OA\Post(
     * path="/presets/{preset:slug}/courses",
     * summary="Presets Courses Assign",
     * description="Assign a course to the preset",
     * operationId="presetsAssignCourse",
     * tags={"Presets"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="preset:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Slug of a course you want to assign",
     *    @OA\JsonContent(
     *       required={"course"},
     *       @OA\Property(property="course", type="string", example="adobe-illustrator"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Course has been assigned",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Specified course has been assigned to the preset."),
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
     *     description="Course has not been assigned due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="You can't assign a course to the preset twice."),
     *     )
     *  ),
     * )
     * @param \App\Models\Preset $preset
     * @param \App\Http\Requests\CourseAssignmentRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Preset $preset, CourseAssignmentRequest $request)
    {
        $preset->assignCourse($request->getCourse());

        return response(['message' => 'Specified course has been assigned to the preset.'], 200);
    }

    /**
     * @OA\Delete(
     * path="/presets/{preset:slug}/courses/{course:slug}",
     * summary="Presets Courses Unassign",
     * description="Unassign a course from the preset",
     * operationId="presetsUnassignCourse",
     * tags={"Presets"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="preset:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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
     *    description="Course has been unassigned",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Specified course has been unassigned from the preset."),
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
     *     description="Course has not been unassigned due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="You can't unassign an unsigned course from the preset."),
     *     )
     *  ),
     * )
     * @param \App\Models\Preset $preset
     * @param \App\Models\Course $course
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Preset $preset, Course $course)
    {
        $preset->unassignCourse($course);

        return response(['message' => 'Specified course has been unassigned from the preset.'], 200);
    }
}
