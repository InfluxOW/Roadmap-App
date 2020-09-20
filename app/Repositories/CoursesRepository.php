<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CoursesRepository
{
    private const WITH = [
        'level',
        'completions.employee.company',
        'completions.employee.teams',
        'completions.employee.technologies',
        'completions.employee.directions',
        'technologies'
    ];

    public function index(Request $request)
    {
        return  QueryBuilder::for(Course::class)
            ->allowedFilters([
                'name',
                'source',
                AllowedFilter::callback('levels', function (Builder $query, $levels) {
                    return $query->whereHas('level', function (Builder $query) use ($levels) {
                        return $query->whereIn('name', (array) $levels);
                    });
                }),
                AllowedFilter::callback('technologies', function (Builder $query, $technologies) {
                    return $query->whereHas('technologies', function (Builder $query) use ($technologies) {
                        return $query->whereIn('name', (array) $technologies);
                    });
                }),
                AllowedFilter::callback('presets', function (Builder $query, $presets) {
                    return $query->whereHas('presets', function (Builder $query) use ($presets) {
                        return $query->whereIn('name', (array) $presets);
                    });
                }),
                AllowedFilter::callback('completed_by', function (Builder $query, $employees) {
                    return $query->whereHas('completions', function (Builder $query) use ($employees) {
                        return $query->whereHas('employee', function (Builder $query) use ($employees) {
                            return $query->whereIn('name', (array) $employees);
                        });
                    });
                }),
            ])
            ->allowedSorts([
                AllowedSort::field('name'),
                AllowedSort::field('source'),
                AllowedSort::field('level', 'employee_level_id'),
            ])
            ->with(self::WITH)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }

    public function show(Request $request)
    {
        return Course::whereSlug($request->route('course'))->with(self::WITH)->firstOrFail();
    }
}
