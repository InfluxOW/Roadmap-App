<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewProfiles',
            'show' => 'viewProfile',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    /**
     * @OA\Get(
     * path="/profiles",
     * summary="Profiles Index",
     * description="View all user profiles (now only redirects user to his profile)",
     * operationId="profilesIndex",
     * tags={"Profiles"},
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
        return redirect()->route('profiles.show', $request->user());
    }

    /**
     * @OA\Get(
     * path="/profiles/{user:username}",
     * summary="Profiles Show",
     * description="View a specific user profile",
     * operationId="profilesShow",
     * tags={"Profiles"},
     * security={
     *   {"access_token": {}},
     * },
     *   @OA\Parameter(
     *      name="user:username",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *    name="show[user]",
     *    in="query",
     *    description="User model attributes that should be returned (by default returns all)",
     *    required=false,
     *    @OA\Schema(
     *         type="string"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Specified user profile has been fetched",
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
     *      description="Specified user has not been found"
     *   ),
     * )
     * @param \App\Models\User $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }
}
