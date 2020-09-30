<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Models\Team;
use App\Models\UserTypes\Employee;
use Illuminate\Http\Request;

class TeamEmployeesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Team::class);
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
     * path="/teams/{team:slug}/employees",
     * summary="Teams Employees Assign",
     * description="Assign an employee to the team",
     * operationId="teamsAssignEmployee",
     * tags={"Teams"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="team:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Username of an employee you want to assign",
     *    @OA\JsonContent(
     *       required={"employee"},
     *       @OA\Property(property="employee", type="string", example="john_doe"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Employee has been assigned",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Specified employee has been assigned to the team."),
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
     *      description="Specified team has not been found"
     *   ),
     * @OA\Response(
     *     response=422,
     *     description="Employee has not been assigned due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="You can't assign a employee to the team twice."),
     *     )
     *  ),
     * )
     * @param \App\Models\Team $team
     * @param \App\Http\Requests\TeamMemberRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Team $team, TeamMemberRequest $request)
    {
        $team->assignEmployee($request->getEmployee());

        return response(['message' => 'Specified employee has been assigned to the team.'], 200);
    }

    /**
     * @OA\Delete(
     * path="/teams/{team:slug}/employees/{employee:username}",
     * summary="Teams Employees Unassign",
     * description="Unassign an employee from the team",
     * operationId="teamsUnassignEmployee",
     * tags={"Teams"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="team:slug",
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
     *    response=200,
     *    description="Employee has been unassigned",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Specified employee has been unassigned from the team."),
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
     *      description="Specified team has not been found"
     *   ),
     * @OA\Response(
     *     response=422,
     *     description="Employee has not been unassigned due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="You can't unassign an unsigned employee from the team."),
     *     )
     *  ),
     * )
     * @param \App\Models\Team $team
     * @param \App\Models\UserTypes\Employee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Team $team, Employee $employee)
    {
        $team->unassignEmployee($employee);

        return response(['message' => 'Specified employee has been unassigned from the team.'], 200);
    }
}
