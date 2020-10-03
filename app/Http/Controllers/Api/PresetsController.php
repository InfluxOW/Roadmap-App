<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Http\Resources\UserBasicInformationResource;
use App\Models\Preset;
use App\Repositories\PresetsRepository;
use Illuminate\Http\Request;

class PresetsController extends Controller
{
    protected PresetsRepository $repository;

    public function __construct(PresetsRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(Preset::class);
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['before', 'index', 'show', 'store'];
    }

    /**
     * @OA\Get(
     * path="/presets",
     * summary="Presets Index",
     * description="View all presets",
     * operationId="presetsIndex",
     * tags={"Presets"},
     * security={
     *   {"access_token": {}},
     * },
     *  @OA\Parameter(
     *    name="filter[name]",
     *    in="query",
     *    description="Filter presets by specific name",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[creator]",
     *    in="query",
     *    description="Filter presets by specific creator name, username or email",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[courses]",
     *    in="query",
     *    description="Filter presets by specific course",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort presets by one of the available params: name, courses_count or manager. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
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
     *  @OA\Parameter(
     *    name="show[preset]",
     *    in="query",
     *    description="Preset model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[course]",
     *    in="query",
     *    description="Preset courses models attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[courses]",
     *    in="query",
     *    description="Count of course models that should be returned under 'courses' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[assigned_to]",
     *    in="query",
     *    description="Count of UserBasicInformation models that should be returned under 'assigned_to' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Presets were fetched",
     *     @OA\JsonContent(
     *     @OA\Property(
     *      property="presets",
     *      type="object",
     *      collectionFormat="multi",
     *       @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/Preset",
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
        $presets = $this->repository->index($request);

        return PresetResource::collection($presets);
    }

    /**
     * @OA\Get(
     * path="/presets/{preset:slug}",
     * summary="Presets Show",
     * description="View a specific preset",
     * operationId="presetsShow",
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
     *  @OA\Parameter(
     *    name="show[preset]",
     *    in="query",
     *    description="Preset model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[course]",
     *    in="query",
     *    description="Preset courses models attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[courses]",
     *    in="query",
     *    description="Count of course models that should be returned under 'courses' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[assigned_to]",
     *    in="query",
     *    description="Count of UserBasicInformation models that should be returned under 'assigned_to' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Specified preset has been fetched",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Preset",
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
     *      description="Specified preset has not been found"
     *   ),
     * )
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\PresetResource
     */
    public function show(Request $request)
    {
        $preset = $this->repository->show($request);

        return new PresetResource($preset);
    }

    /**
     * @OA\Post(
     * path="/presets",
     * summary="Presets Store",
     * description="Store a new preset",
     * operationId="presetsStore",
     * tags={"Presets"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Preset information",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="Basic PHP prest"),
     *       @OA\Property(property="description", type="string", example="Start preset to become a PHP developer"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Specified preset has been stored",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Preset",
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
     *     description="Preset has not been stored due to validation error",
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
     * @param \App\Http\Requests\PresetRequest $request
     * @return \App\Http\Resources\PresetResource
     */
    public function store(PresetRequest $request)
    {
        $preset = $this->repository->store($request);

        return new PresetResource($preset);
    }

    /**
     * @OA\Put(
     * path="/presets/{preset:slug}",
     * summary="Preset Update",
     * description="Update preset information",
     * operationId="presetsUpdate",
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
     *    description="Preset information",
     *    @OA\JsonContent(
     *       required={"name", "description"},
     *       @OA\Property(property="name", type="string", example="Basic PHP prest"),
     *       @OA\Property(property="description", type="string", example="Start preset to become a PHP developer"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Specified preset has been updated",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Preset",
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
     *      description="Specified preset has not been found"
     *   ),
     * @OA\Response(
     *     response=422,
     *     description="Preset has not been updated due to validation error",
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
     * @param \App\Http\Requests\PresetRequest $request
     * @param \App\Models\Preset $preset
     * @return \App\Http\Resources\PresetResource
     */
    public function update(PresetRequest $request, Preset $preset)
    {
        $preset->update($request->validated());

        return new PresetResource($preset);
    }

    /**
     * @OA\Delete(
     * path="/presets/{preset:slug}",
     * summary="Preset Destroy",
     * description="Delete a specific preset",
     * operationId="presetsDestroy",
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
     * @OA\Response(
     *    response=204,
     *    description="Specified preset has been deleted",
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
     *      description="Specified preset has not been found"
     *   ),
     * )
     * @param \App\Models\Preset $preset
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Preset $preset)
    {
        $preset->delete();

        return response()->noContent();
    }
}
