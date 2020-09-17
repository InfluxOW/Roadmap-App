<?php

namespace App\Http\Controllers\Api\Dashboards;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboards\ManagerDashboardResource;
use App\Repositories\Dashboards\ManagerDashboardRepository;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    protected ManagerDashboardRepository $repository;

    public function __construct(ManagerDashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        $manager = $this->repository->index($request);

        $this->authorize('viewManagerDashboard', $manager);

        return new ManagerDashboardResource($manager);
    }
}
