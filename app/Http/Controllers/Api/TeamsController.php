<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Team::class);
    }

    /**
     * @OA\Post(
     * path="/teams",
     * summary="Teams Store",
     * description="Store a new team",
     * operationId="teamsStore",
     * tags={"Teams"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Team information",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="Antifraud team"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Specified team has been stored",
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
     *     description="Team has not been stored due to validation error",
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
     * @param \App\Http\Requests\TeamRequest $request
     * @return \App\Http\Resources\PresetResource
     */
    public function store(TeamRequest $request)
    {
        $request->user()->teams()->create($request->validated());

        return response(['message' => 'Team has been created.'], 201);
    }

    /**
     * @OA\Put(
     * path="/teams/{team:slug}",
     * summary="Team Update",
     * description="Update team information",
     * operationId="teamsUpdate",
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
     *    description="Team information",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="Antifraud team"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Specified team has been updated",
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
     *     description="Team has not been updated due to validation error",
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
     * @param \App\Models\Team $team
     * @param \App\Http\Requests\TeamRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Team $team, TeamRequest $request)
    {
        $team->update($request->validated());

        return response(['message' => 'Team has been updated.'], 200);
    }

    /**
     * @OA\Delete(
     * path="/teams/{team:slug}",
     * summary="Team Destroy",
     * description="Delete a specific team",
     * operationId="teamsDestroy",
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
     * @OA\Response(
     *    response=204,
     *    description="Specified team has been deleted",
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
     *    description="Specified team has not been found"
     *   ),
     * )
     * @param \App\Models\Team $team
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response()->noContent();
    }
}
