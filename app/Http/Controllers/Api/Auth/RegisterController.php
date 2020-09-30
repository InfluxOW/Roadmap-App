<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Company;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     * path="/register",
     * summary="Register",
     * description="Register a new user",
     * operationId="authRegister",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="User data",
     *    @OA\JsonContent(
     *       required={"invite_token", "name", "username", "password", "password_confirmation"},
     *       @OA\Property(property="invite_token", type="string", example="niUljlJf2jUB9XxxQW8n5BlHfKsL9RFNfXCMc8viDof3MtsHpRoQUkoMllrW"),
     *       @OA\Property(property="name", type="string", example="John Doe"),
     *       @OA\Property(property="username", type="string", example="john_doe"),
     *       @OA\Property(property="password", type="string", example="password"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User has been registered",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You were successfully registered. Use your email and password to sign in."),
     *    )
     *     ),
     * @OA\Response(
     *     response=422,
     *     description="Registration failed due to validation error",
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
     *                 example={"The email must be a valid email address."},
     *              )
     *           )
     *        )
     *     )
     *  )
     * )
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $invite = tap(Invite::whereCode($request->invite_token)->first(), function ($invite) {
            $invite->revoke();
        });

        $user = $this->createUser($invite, $request);

        if ($user->isManager()) {
            $this->createManagerDefaultTeam($user);
        }

        if ($user->isEmployee() && $invite->sender->isManager()) {
            $this->joinManagerDefaultTeam($user, $invite->sender);
        }

        return response(
            ['message' => 'You were successfully registered. Use your email and password to sign in.'],
            200
        );
    }

    private function createManagerDefaultTeam($manager)
    {
        $manager->teams()->create(['name' => 'Default Team']);
    }

    private function joinManagerDefaultTeam(Employee $employee, Manager $manager)
    {
        $team = $manager->teams->where('name', 'Default Team')->first();
        $employee->teams()->attach($team, ['assigned_at' => now()]);
    }

    private function createUser(Invite $invite, RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['email'] = $invite->email;
        $validatedData['role'] = $invite->role;
        $validatedData['password'] = bcrypt($request->password);

        if ($invite->role === 'employee') {
            $user = Employee::make($validatedData);
        }

        if ($invite->role === 'manager') {
            $user = Manager::make($validatedData);
        }

        $user->company()->associate($invite->company);
        $user->save();

        return $user;
    }
}
