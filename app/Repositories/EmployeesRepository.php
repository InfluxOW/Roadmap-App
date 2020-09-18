<?php

namespace App\Repositories;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeesRepository
{
    private const WITH = [
        'technologies',
        'directions',
        'teams',
        'presets',
        'completions',
        'company'
    ];

    private const WITH_COUNT = [
        'completions',
    ];

    public function index(Request $request)
    {
        $query = $request->user()->isAdmin() ?
            Employee::query() :
            $request->user()->employees();

        return  QueryBuilder::for($query)
            ->allowedFilters([
                'name',
                'username',
                'email',
                'sex',
                'position',
                AllowedFilter::callback('technologies', function (Builder $query, $technologies) {
                    return $query->whereHas('technologies', function (Builder $query) use ($technologies) {
                        return $query->whereIn('name', (array) $technologies);
                    });
                }),
                AllowedFilter::callback('directions', function (Builder $query, $directions) {
                    return $query->whereHas('directions', function (Builder $query) use ($directions) {
                        return $query->whereIn('name', (array) $directions);
                    });
                }),
                AllowedFilter::callback('teams', function (Builder $query, $teams) {
                    return $query->whereHas('teams', function (Builder $query) use ($teams) {
                        return $query->whereIn('name', (array) $teams);
                    });
                }),
                AllowedFilter::callback('presets', function (Builder $query, $presets) {
                    return $query->whereHas('presets', function (Builder $query) use ($presets) {
                        return $query->whereIn('name', (array) $presets);
                    });
                }),
                AllowedFilter::callback('completions', function (Builder $query, $courses) {
                    return $query->whereHas('completions', function (Builder $query) use ($courses) {
                        return $query->whereHas('course', function (Builder $query) use ($courses) {
                            return $query->whereIn('name', (array) $courses);
                        });
                    });
                }),
            ])
            ->allowedSorts([
                AllowedSort::field('name'),
                AllowedSort::field('username'),
                AllowedSort::field('completions_count'),
            ])
            ->with(self::WITH)
            ->withCount(self::WITH_COUNT)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }
}