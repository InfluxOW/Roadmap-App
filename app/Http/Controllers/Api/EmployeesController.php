<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Repositories\EmployeesRepository;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    protected EmployeesRepository $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(User::class, 'employee');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewEmployees',
            'show' => 'viewEmployee',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    /**
     * @OA\Get(
     * path="/employees",
     * summary="Employees Index",
     * description="View all employees",
     * operationId="employeesIndex",
     * tags={"Employees"},
     * security={
     *   {"access_token": {}},
     * },
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
            return redirect()->route('employees.show', $request->user());
        }

        $users = $this->repository->index($request);

        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     * path="/employees/{employee:slug}",
     * summary="Employees Show",
     * description="View a specific employee",
     * operationId="employeesShow",
     * tags={"Employees"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="employee:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *    name="show[user]",
     *    in="query",
     *    description="Employee model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Specified employee has been fetched",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Employee",
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
     * @return \App\Http\Resources\UserResource
     */
    public function show(Employee $employee, Request $request)
    {
        return new UserResource($employee);
    }
}
