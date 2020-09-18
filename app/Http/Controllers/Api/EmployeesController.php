<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoursesResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Repositories\EmployeesRepository;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    protected EmployeesRepository $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $this->authorize('viewEmployees', User::class);

        $users = $this->repository->index($request);

        return UsersResource::collection($users);
    }
}
