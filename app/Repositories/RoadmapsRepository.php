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
        'preset.manager.company',
        'preset.roadmaps.employee',
        'employee',
        'manager.company'
    ];

    public function index(Request $request)
    {
        $query = $request->user()->isAdmin() ?
            Roadmap::query() :
            $request->user()->roadmaps();

        return $query
                ->with(self::WITH)
                ->latest('assigned_at')
                ->paginate($request->per ?? 20)
                ->appends(request()->query());
    }

    public function show(Request $request)
    {
        $roadmaps = $request->user()->isManager() ?
            $request->route('employee')->roadmaps()->where('manager_id', $request->user()->id) :
            $request->route('employee')->roadmaps();

        return $roadmaps
            ->with(self::WITH)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }
}
