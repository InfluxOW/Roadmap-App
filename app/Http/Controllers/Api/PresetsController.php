<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Preset;
use App\Repositories\PresetsRepository;
use Illuminate\Http\Request;

class PresetsController extends Controller
{
    protected PresetsRepository $repository;

    public function __construct(PresetsRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(Preset::class);
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['before', 'index', 'show', 'store'];
    }

    public function index(Request $request)
    {
        $presets = $this->repository->index($request);

        return PresetResource::collection($presets);
    }

    public function show(Request $request)
    {
        $preset = $this->repository->show($request);

        return new PresetResource($preset);
    }

    public function store(PresetRequest $request)
    {
        $preset = $this->repository->store($request);

        return new PresetResource($preset);
    }

    public function update(PresetRequest $request, Preset $preset)
    {
        $preset->update($request->validated());

        return new PresetResource($preset);
    }

    public function destroy(Preset $preset)
    {
        $preset->delete();

        return response()->noContent();
    }
}
