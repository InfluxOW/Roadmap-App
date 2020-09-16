<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoadmapRequest;
use App\Models\Preset;
use App\Models\User;
use App\Repositories\RoadmapsRepository;
use Illuminate\Http\Request;

class RoadmapsController extends Controller
{
    protected RoadmapsRepository $repository;

    public function __construct(RoadmapsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(RoadmapRequest $request)
    {
        $this->authorize('manageRoadmaps', User::class);

        $this->repository->store($request);

        return response(['message' => "Roadmap for the specified user has been created"], 201);
    }

    public function destroy(Request $request)
    {
        $this->authorize('manageRoadmaps', User::class);

        $this->repository->destroy($request);

        return response()->noContent();
    }
}
