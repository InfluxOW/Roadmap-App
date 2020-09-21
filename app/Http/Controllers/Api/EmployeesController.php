<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

        $this->authorizeResource(User::class, 'employee');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewEmployees',
            'show' => 'viewEmployee',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    public function index(Request $request)
    {
        if ($request->user()->isEmployee()) {
            return redirect()->route('employees.show', $request->user());
        }

        $users = $this->repository->index($request);

        return UserResource::collection($users);
    }

    public function show(Employee $employee, Request $request)
    {
        return new UserResource($employee);
    }
}
