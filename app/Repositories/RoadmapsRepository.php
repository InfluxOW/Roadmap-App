<?php

namespace App\Repositories;

use App\Models\Roadmap;
use Illuminate\Http\Request;

class RoadmapsRepository
{
    private const WITH = [
        'preset.courses.completions',
        'preset.courses.level',
        'preset.courses.technologies',
        'preset.manager',
        'preset.roadmaps.employee',
        'employee',
    ];

    public function index(Request $request)
    {
        $query = $request->user()->isAdmin() ?
            Roadmap::query() :
            $request->user()->roadmaps();

        return $query
                ->with(self::WITH)
                ->paginate($request->per ?? 20)
                ->appends(request()->query());
    }

    public function show(Request $request)
    {
        return $request->route('employee')
            ->roadmaps()
            ->with(self::WITH)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }
}
