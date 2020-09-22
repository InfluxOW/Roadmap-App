<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTypes\Manager;
use Illuminate\Http\Request;

class ManagersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'manager');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewManagers',
            'show' => 'viewManager',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    /**
     * @OA\Get(
     * path="/managers",
     * summary="Managers Index",
     * description="View all managers (for admin) | now only redirects authenticated manager to his profile",
     * operationId="managersIndex",
     * tags={"Managers"},
     * security={
     *   {"access_token": {}},
     * },
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
        if ($request->user()->isManager()) {
            return redirect()->route('managers.show', $request->user());
        }

        // TODO: admin logic for viewing managers
    }

    /**
     * @OA\Get(
     * path="/managers/{manager:slug}",
     * summary="Managers Show",
     * description="View a specific manager",
     * operationId="managersShow",
     * tags={"Managers"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="manager:slug",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *    name="show[user]",
     *    in="query",
     *    description="Manager model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Specified manager has been fetched",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Manager",
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
     *      description="Specified manager has not been found"
     *   ),
     * )
     * @param \App\Models\UserTypes\Manager $manager
     * @return \App\Http\Resources\UserResource
     */
    public function show(Manager $manager)
    {
        return new UserResource($manager);
    }
}
