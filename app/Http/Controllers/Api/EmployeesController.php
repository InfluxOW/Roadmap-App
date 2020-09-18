<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoursesResource;
use App\Http\Resources\UsersResource;
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
    }

    public function index(Request $request)
    {
        $this->authorize('viewEmployees', User::class);

        if ($request->user()->isEmployee()) {
            return redirect()->route('employees.show', $request->user());
        }

        $users = $this->repository->index($request);

        return UsersResource::collection($users);
    }

    public function show(Employee $employee, Request $request)
    {
        $this->authorize('viewEmployee', $employee);

        return new UsersResource($employee);
    }
}
