<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TechnologyResource;
use App\Http\Resources\UserBasicInformationResource;
use App\Models\Technology;
use App\Repositories\TechnologiesRepository;
use Illuminate\Http\Request;

class TechnologiesController extends Controller
{
    protected TechnologiesRepository $repository;

    public function __construct(TechnologiesRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(Technology::class);
    }

    /**
     * @OA\Get(
     * path="/technologies",
     * summary="Technologies Index",
     * description="View all technologies",
     * operationId="technologiesIndex",
     * tags={"Technologies"},
     * security={
     *   {"access_token": {}},
     * },
     *  @OA\Parameter(
     *    name="filter[name]",
     *    in="query",
     *    description="Filter technologies by specific name",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[courses]",
     *    in="query",
     *    description="Filter technologies by specific courses",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[employees]",
     *    in="query",
     *    description="Filter technologies by possessing by specific employees",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort technologies by one of the available params: name or courses_count. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
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
     *    name="show[course]",
     *    in="query",
     *    description="Technology model attributes that should be returned (by default returns all)",
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
     *    name="take[possessed_by]",
     *    in="query",
     *    description="Count of UserBasicInformation models that should be returned under 'possessed_by' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Technologies were fetched",
     *     @OA\JsonContent(
     *     @OA\Property(
     *      property="technologies",
     *      type="object",
     *      collectionFormat="multi",
     *       @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/Technology",
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
     * @OA\Response(
     *     response=422,
     *     description="Technologies were not shown due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="You cannot filter by not yours employees."),
     *     )
     *  ),
     * )
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $technologies = $this->repository->index($request);

        return TechnologyResource::collection($technologies);
    }
}
