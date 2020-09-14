<?php

namespace App\Http\Controllers\Api\Dashboards;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeDashboardResource;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    protected DashboardRepository $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        $employee = $this->repository->employee($request);

        $this->authorize('viewEmployeeDashboard', $employee);

        return new EmployeeDashboardResource($employee);
    }
}
