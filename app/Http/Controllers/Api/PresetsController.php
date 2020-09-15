<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetsResource;
use App\Models\Preset;
use App\Repositories\PresetsRepository;
use Illuminate\Http\Request;

class PresetsController extends Controller
{
    protected PresetsRepository $repository;

    public function __construct(PresetsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $this->authorize(Preset::class);

        $presets = $this->repository->index($request);

        return PresetsResource::collection($presets);
    }

    public function show(Request $request)
    {
        $this->authorize(Preset::class);

        $preset = $this->repository->show($request);

        return new PresetsResource($preset);
    }

    public function store(PresetRequest $request)
    {
        $this->authorize(Preset::class);

        $preset = $this->repository->store($request);

        return new PresetsResource($preset);
    }

    public function update(PresetRequest $request, Preset $preset)
    {
        $this->authorize($preset);

        $preset->update($request->validated());

        return new PresetsResource($preset);
    }

    public function destroy(Preset $preset)
    {
        $this->authorize($preset);

        $preset->delete();

        return response()->noContent();
    }
}
