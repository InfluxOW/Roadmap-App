<?php

namespace App\Http\Controllers\Api\Dashboards;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboards\EmployeeDashboardResource;
use App\Repositories\DashboardRepository;
use App\Repositories\Dashboards\EmployeeDashboardRepository;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    protected EmployeeDashboardRepository $repository;

    public function __construct(EmployeeDashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        $employee = $this->repository->index($request);

        $this->authorize('viewEmployeeDashboard', $employee);

        return new EmployeeDashboardResource($employee);
    }
}
