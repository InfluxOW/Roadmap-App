<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoadmapRequest;
use App\Http\Resources\RoadmapResource;
use App\Models\Roadmap;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Repositories\RoadmapsRepository;
use Illuminate\Http\Request;

class RoadmapsController extends Controller
{
    protected RoadmapsRepository $repository;

    public function __construct(RoadmapsRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(User::class, 'employee');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewEmployeesRoadmaps',
            'show' => 'viewEmployeeRoadmaps',
            'store' => 'manageRoadmaps',
            'destroy' => 'manageRoadmaps',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'destroy', 'store'];
    }

    /**
     * @OA\Get(
     * path="/roadmaps",
     * summary="Roadmaps Index",
     * description="View all roadmaps",
     * operationId="roadmapsIndex",
     * tags={"Roadmaps"},
     * security={
     *   {"access_token": {}},
     * },
     *  @OA\Parameter(
     *    name="filter[name]",
     *    in="query",
     *    description="Filter roadmaps by specific name",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[creator]",
     *    in="query",
     *    description="Filter roadmaps by specific creator name, username or email",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[courses]",
     *    in="query",
     *    description="Filter roadmaps by specific course",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort roadmaps by one of the available params: name, courses_count or manager. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
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
     *    name="show[roadmap]",
     *    in="query",
     *    description="Roadmap model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[preset]",
     *    in="query",
     *    description="Roadmap preset model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[course]",
     *    in="query",
     *    description="Roadmap preset courses models attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[user]",
     *    in="query",
     *    description="Roadmap assigned_by Manager model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Roadmaps were fetched",
     *     @OA\JsonContent(
     *     @OA\Property(
     *      property="roadmaps",
     *      type="object",
     *      collectionFormat="multi",
     *       @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/Roadmap",
     *        )
     *      ),
     *    )
     *   )
     *  ),
     * @OA\Response(
     *    response=301,
     *    description="Redirected",
     * ),
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
        if ($request->user()->isEmployee()) {
            return redirect()->route('roadmaps.show', $request->user());
        }

        $roadmaps = $this->repository->index($request);

        return RoadmapResource::collection($roadmaps);
    }

    /**
     * @OA\Get(
     * path="/roadmaps/{roadmap:slug}",
     * summary="Roadmaps Show",
     * description="View a specific employee roadmaps",
     * operationId="roadmapsShow",
     * tags={"Roadmaps"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="roadmap:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *    name="show[roadmap]",
     *    in="query",
     *    description="Roadmap model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[preset]",
     *    in="query",
     *    description="Roadmap preset model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[course]",
     *    in="query",
     *    description="Roadmap preset courses models attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="show[user]",
     *    in="query",
     *    description="Roadmap assigned_by Manager model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Specified employee roadmaps were fetched",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Roadmap",
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
     *      description="Specified employee has not been found"
     *   ),
     * )
     * @param \App\Models\UserTypes\Employee $employee
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Employee $employee, Request $request)
    {
        $roadmaps = $this->repository->show($request);

        return RoadmapResource::collection($roadmaps);
    }

    /**
     * @OA\Post(
     * path="/roadmaps",
     * summary="Roadmaps Store",
     * description="Store a new roadmap",
     * operationId="roadmapsStore",
     * tags={"Roadmaps"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Roadmap information",
     *    @OA\JsonContent(
     *       required={"employee", "preset"},
     *       @OA\Property(property="employee", type="string", example="vasya.pupkin"),
     *       @OA\Property(property="preset", type="string", example="best-php-preset-ever"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Specified roadmap has been stored",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Roadmap",
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
     *     description="Roadmap has not been stored due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="employee",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The employee attribute is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Http\Requests\RoadmapRequest $request
     * @return \App\Http\Resources\PresetResource
     */
    public function store(RoadmapRequest $request)
    {
        Roadmap::createFromRequest($request);

        return response(['message' => "Roadmap for the specified user has been created"], 201);
    }

    /**
     * @OA\Delete(
     * path="/roadmaps/{preset:slug}/{employee:username}",
     * summary="Roadmap Destroy",
     * description="Delete a specific roadmap",
     * operationId="roadmapsDestroy",
     * tags={"Roadmaps"},
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
     *      name="employee:username",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Response(
     *    response=204,
     *    description="Specified roadmap has been deleted",
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
     *      description="Specified employee has not been found"
     *   ),
     * )
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Roadmap::deleteByRequest($request);

        return response()->noContent();
    }
}
