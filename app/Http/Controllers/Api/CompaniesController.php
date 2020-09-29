<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Company;
use Facades\App\Repositories\EmployeesRepository;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    /**
     * @OA\Get(
     * path="/companies",
     * summary="Companies Index",
     * description="View all companies (now only redirects managers to their company page)",
     * operationId="companiesIndex",
     * tags={"Companies"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\Response(
     *    response=302,
     *    description="Redirected",
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
        if ($request->user()->isManager()) {
            return redirect()->route('companies.show', $request->user()->company);
        }

        // TODO: show all companies for admin
    }

    /**
     * @OA\Get(
     * path="/companies/{company:slug}",
     * summary="Companies Show",
     * description="View a specific company employees",
     * operationId="companiesShow",
     * tags={"Companies"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="company:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *    name="filter[name]",
     *    in="query",
     *    description="Filter employees by specific name",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[username]",
     *    in="query",
     *    description="Filter employees by specific username",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[email]",
     *    in="query",
     *    description="Filter employees by specific email",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[sex]",
     *    in="query",
     *    description="Filter employees by specific sex",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[position]",
     *    in="query",
     *    description="Filter employees by specific position",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[technologies]",
     *    in="query",
     *    description="Filter employees by having specific technologies",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[directions]",
     *    in="query",
     *    description="Filter employees by having specific development directions",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[teams]",
     *    in="query",
     *    description="Filter employees by participation in the specific teams",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[presets]",
     *    in="query",
     *    description="Filter employees by having specific presets",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[courses]",
     *    in="query",
     *    description="Filter employees by having specific courses",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[completions]",
     *    in="query",
     *    description="Filter employees by having specific courses completed",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="filter[manager]",
     *    in="query",
     *    description="Filter employees by having a specific manager",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="sort",
     *    in="query",
     *    description="Sort employees by one of the available params: name, username or completions_count. Default sort direction is ASC. To apply DESC sort add '-' symbol before the param name.",
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
     *    name="show[user]",
     *    in="query",
     *    description="Employee model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[teams]",
     *    in="query",
     *    description="Count of team models that should be returned under 'teams' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[technologies]",
     *    in="query",
     *    description="Count of technology models that should be returned under 'technologies' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="take[development_directions]",
     *    in="query",
     *    description="Count of DevelopmentDirection models that should be returned under 'development_directions' key (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Employees were fetched",
     *     @OA\JsonContent(
     *     @OA\Property(
     *      property="employees",
     *      type="object",
     *      collectionFormat="multi",
     *       @OA\Property(
     *         property="0",
     *         type="array",
     *         collectionFormat="multi",
     *         @OA\Items(
     *           type="object",
     *           ref="#/components/schemas/Employee",
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
     *    response=404,
     *    description="Specified company has not been found"
     * ),
     * )
     * @param \App\Models\Company $company
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Company $company, Request $request)
    {
        if ($request->user()->isManager()) {
            $employees = EmployeesRepository::show($company);

            return UserResource::collection($employees);
        }

        // TODO: show company for admin
    }
}
