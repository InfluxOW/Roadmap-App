<?php

namespace App\Repositories;

use App\Models\Preset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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
        'courses.completions'
    ];

    private const WITH_COUNT = [
        'courses',
    ];

    public function index(Request $request)
    {
        return  QueryBuilder::for(Preset::class)
            ->allowedFilters([
                'name',
                AllowedFilter::callback('creator', function (Builder $query, $managers) {
                    return $query->whereHas('manager', function (Builder $query) use ($managers) {
                        return $query->whereIn('name', (array) $managers)
                            ->orWhereIn('username', (array) $managers)
                            ->orWhereIn('email', (array) $managers);
                    });
                }),
                AllowedFilter::callback('courses', function (Builder $query, $courses) {
                    return $query->whereHas('courses', function (Builder $query) use ($courses) {
                        return $query->whereIn('name', (array) $courses)->orWhereIn('slug', (array) $courses);
                    });
                }),
            ])
            ->allowedSorts([
                AllowedSort::field('name'),
                AllowedSort::field('courses_count'),
                AllowedSort::field('manager', 'manager_id'),
            ])
            ->with(self::WITH)
            ->withCount(self::WITH_COUNT)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
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
