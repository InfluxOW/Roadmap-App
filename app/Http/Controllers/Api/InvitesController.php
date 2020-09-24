<?php

namespace App\Http\Controllers\Api;

use App\Events\InviteCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;
use App\Models\Invite;

class InvitesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Invite::class);
    }

    /**
     * @OA\Post(
     * path="/invites",
     * summary="Invites Store",
     * description="Store a new invite",
     * operationId="invitesStore",
     * tags={"Invites"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Invite information",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", example="john_doe@mail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Invite has been sent",
     *   @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You have successfully invited a new user!"),
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
     *     response=422,
     *     description="Invite has not been sent due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="email",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The email attribute is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Http\Requests\InviteRequest $request
     * @return \App\Http\Resources\PresetResource
     */
    public function store(InviteRequest $request)
    {
        $invite = Invite::createFromRequest($request);

        InviteCreated::dispatch($invite);

        return response(['message' => "You have successfully invited a new user!"], 200);
    }
}
