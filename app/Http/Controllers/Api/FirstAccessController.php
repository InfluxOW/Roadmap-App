<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FirstAccessRequest;
use App\Http\Requests\InviteRequest;
use App\Models\Company;
use App\Models\Invite;
use App\Models\UserTypes\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FirstAccessController extends Controller
{
    /**
     * @OA\Post(
     * path="/first_access",
     * summary="First Access",
     * description="Ask for a first access",
     * operationId="firstAccess",
     * tags={"First Access"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="First Access Information",
     *    @OA\JsonContent(
     *       required={"email", "company"},
     *       @OA\Property(property="email", type="string", example="john_doe@mail.com"),
     *       @OA\Property(property="company", type="array", @OA\Items(type="string"), ref="#/components/schemas/Company")
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
     * @param \App\Http\Requests\FirstAccessRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(FirstAccessRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->createFirstAccessInvite(
                $request->email,
                Company::create($request->company)
            );
        });

        return response(['message' => "You were invited to join the application! Check your email, please."], 200);
    }

    private function createFirstAccessInvite(string $email, Company $company)
    {
        $request = new InviteRequest();

        $request->setUserResolver(function () {
            return Admin::first();
        });
        $request->replace(['email' => $email, 'role' => 'manager', 'company' => $company->slug]);

        (new InvitesController())->store($request);
    }
}
