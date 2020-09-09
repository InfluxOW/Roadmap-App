<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign In",
     * description="Login by email and password",
     * operationId="authLogin",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *       @OA\Property(property="remember", type="boolean", example=true),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful login",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource"),
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMDM2N2RkZTczY2NiYmE2ZjRkYTkxY2VjOWRlODA0NmYzYjNkYTI5MWRlZjM4Mjg1OTliZTY5YzAxOTUyMTIwMGIwYTU1ZGM5NWEyMzM2NmUiLCJpYXQiOjE1OTg1Mjg1NTYsIm5iZiI6MTU5ODUyODU1NiwiZXhwIjoxNjMwMDY0NTU2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.BfFE40nkUt-Jwr7S8gCvogsCoYsX6VSYvVzuT9czVuGS_VeDFV4W2riQ0SopOeLtsvZttMI8VLgpXDYBYsASsFo4H-zrAmGRYZ2qLi4QNj5cPVJpXqe4xhRQsYbpY4xre7lNfXaYod56Ocek-3psN3A68eTZ2ro_cPde42lkur5eQTx7-TojHeqpK3uywl8IugzvBq3wcfcHLRQZSmEVvkJzYNuLs-03YsMnyod1wk3FszqzqzmZP2hiTXj-HhU1N6WRy6XobGzgoM__bxPRsQoMK3TCphqHIhwO14pJzaFbDqEf3USEMmPrF9rYJrgUjzGUglqRsg78GZsWHNakhH6-q1kibPI-k-VMazKSn85wi6HuXXCwycBXY0PRYpYAGbUrfBkuxK_t21peZ8tb6kD3XEr6XEz3PgEmbaRbnFelQEybjLCGYmWj2yuKOjkSQgNeEdOmpqzUDUiJByjE_ElxRD77prr-OG6e3GwwwOaHYLy6_8MtRP1cTg81BtcEYc8AmFeuceSAEjbDzEzaLSgiAJH9dh3Qy24V5HNnQjXmWvpSAHW1sJcXAYZyPE1bY0h7LBk91UmTJ_mkxa0EDofQsOFMhAQdGrQr2HRdrwfmD_vrHDNL-MeP94XNAihrVg4AXjhTMuZOhuzSxza_DgNO9EACQuzy1f81mfMapGU"),
     *       @OA\Property(property="expires_at", type="string", format="date-time", example="2020-10-07 13:29:30"),
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Login failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again!"),
     *    )
     * ),
     * )
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response(['message' => 'Sorry, wrong email address or password. Please try again!'], 401);
        }

        $token = Auth::user()->createToken('access_token');
        $token->token->expires_at = $request->remember ?
            Carbon::now()->addMonth() :
            Carbon::now()->addDay();
        $token->token->save();

        return response([
            'user' => new UserResource(Auth::user()),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }
}