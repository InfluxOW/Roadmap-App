<?php

namespace App\Repositories;

use App\Models\Preset;
use Illuminate\Http\Request;

class PresetsRepository
{
    private const WITH = [
        'manager.company',
        'roadmaps.employee.company',
        'roadmaps.employee.teams',
        'roadmaps.employee.technologies',
        'roadmaps.employee.directions',
        'courses.level',
        'courses.technologies',
        'courses.completions.employee'
    ];

    public function index(Request $request)
    {
        return Preset::with(self::WITH)->paginate($request->per ?? 20);
    }

    public function show(Request $request)
    {
        return Preset::whereSlug($request->route('preset'))->with(self::WITH)->firstOrFail();
    }

    public function store(Request $request)
    {
        return $request->user()->isAdmin() ?
            Preset::create($request->validated()) :
            $request->user()->presets()->create($request->validated());
    }
}
