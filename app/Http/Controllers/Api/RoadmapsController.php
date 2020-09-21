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

        $this->authorizeResource(User::class, 'employee');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewEmployeesRoadmaps',
            'show' => 'viewEmployeeRoadmaps',
            'store' => 'manageRoadmaps',
            'destroy' => 'manageRoadmaps',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'destroy', 'store'];
    }

    public function index(Request $request)
    {
        if ($request->user()->isEmployee()) {
            return redirect()->route('roadmaps.show', $request->user());
        }

        $roadmaps = $this->repository->index($request);

        return RoadmapResource::collection($roadmaps);
    }

    public function show(Employee $employee, Request $request)
    {
        $roadmaps = $this->repository->show($request);

        return RoadmapResource::collection($roadmaps);
    }

    public function store(RoadmapRequest $request)
    {
        Roadmap::createFromRequest($request);

        return response(['message' => "Roadmap for the specified user has been created"], 201);
    }

    public function destroy(Request $request)
    {
        Roadmap::deleteByRequest($request);

        return response()->noContent();
    }
}
