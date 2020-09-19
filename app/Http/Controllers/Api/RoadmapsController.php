<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoadmapRequest;
use App\Http\Resources\RoadmapResource;
use App\Models\Roadmap;
use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Repositories\RoadmapsRepository;
use Illuminate\Http\Request;

class RoadmapsController extends Controller
{
    protected RoadmapsRepository $repository;

    public function __construct(RoadmapsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $this->authorize('viewEmployeesRoadmaps', User::class);

        if ($request->user()->isEmployee()) {
            return redirect()->route('roadmaps.show', $request->user());
        }

        $roadmaps = $this->repository->index($request);

        return RoadmapResource::collection($roadmaps);
    }

    public function show(Request $request, Employee $employee)
    {
        $this->authorize('viewEmployeeRoadmaps', $employee);

        $roadmaps = $this->repository->show($request);

        return RoadmapResource::collection($roadmaps);
    }

    public function store(RoadmapRequest $request)
    {
        $this->authorize('manageRoadmaps', User::class);

        Roadmap::createFromRequest($request);

        return response(['message' => "Roadmap for the specified user has been created"], 201);
    }

    public function destroy(Request $request)
    {
        $this->authorize('manageRoadmaps', User::class);

        Roadmap::deleteByRequest($request);

        return response()->noContent();
    }
}
