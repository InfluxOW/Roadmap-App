<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetGenerationRequest;
use App\Http\Resources\PresetResource;
use App\Models\Preset;
use App\Repositories\PresetsRepository;
use Illuminate\Http\Request;

class PresetsGenerationController extends Controller
{
    protected PresetsRepository $repository;

    public function __construct(PresetsRepository $repository)
    {
        $this->repository = $repository;

        $this->authorizeResource(Preset::class);
    }

    protected function resourceAbilityMap()
    {
        return [
            'store' => 'generate',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['store'];
    }

    /**
     * @OA\Post(
     * path="/presets/generation",
     * summary="Presets Generate",
     * description="Generate a new preset",
     * operationId="presetsGenerate",
     * tags={"Presets"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\RequestBody(
     *    required=true,
     *    description="Preset information and necessery technologies",
     *    @OA\JsonContent(
     *       required={"name", "description", "technologies"},
     *       @OA\Property(property="name", type="string", example="Basic PHP prest"),
     *       @OA\Property(property="description", type="string", example="Start preset to become a PHP developer"),
     *       @OA\Property(property="technologies", type="array", @OA\Items(type="string"), example={"PHP", "Laravel", "Javascript"}),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Specified preset has been generated",
     *     @OA\JsonContent(
     *     @OA\Items(
     *      type="object",
     *      ref="#/components/schemas/Preset",
     *    )
     *   )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated",
     * ),
     * @OA\Response(
     *    response=403,
     *    description="Unauthorized",
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Preset has not been stored due to validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="name",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The name attribute is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * )
     * @param \App\Http\Requests\PresetGenerationRequest $request
     * @return \App\Http\Resources\PresetResource
     */
    public function store(PresetGenerationRequest $request)
    {
        $preset = $this->repository->generate($request);

        return new PresetResource($preset);
    }
}
