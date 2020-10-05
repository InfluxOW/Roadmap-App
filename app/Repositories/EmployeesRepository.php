<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

    public function show(Company $company)
    {
        return QueryBuilder::for($company->employees())
            ->allowedFilters([
                AllowedFilter::exact('name', 'users.name'),
                'username',
                'email',
                AllowedFilter::exact('sex'),
                'position',
                AllowedFilter::callback('technologies', function (Builder $query, $technologies) {
                    return $query->whereHas('technologies', function (Builder $query) use ($technologies) {
                        return $query->whereIn('name', (array) $technologies)->orWhereIn('slug', (array) $technologies);
                    });
                }),
                AllowedFilter::callback('directions', function (Builder $query, $directions) {
                    return $query->whereHas('directions', function (Builder $query) use ($directions) {
                        return $query->whereIn('name', (array) $directions)->orWhereIn('slug', (array) $directions);
                    });
                }),
                AllowedFilter::callback('teams', function (Builder $query, $teams) {
                    return $query->whereHas('teams', function (Builder $query) use ($teams) {
                        return $query->whereIn('name', (array) $teams)->orWhereIn('slug', (array) $teams);
                    });
                }),
                AllowedFilter::callback('presets', function (Builder $query, $presets) {
                    return $query->whereHas('presets', function (Builder $query) use ($presets) {
                        return $query->whereIn('name', (array) $presets)->orWhereIn('slug', (array) $presets);
                    });
                }),
                AllowedFilter::callback('courses', function (Builder $query, $courses) {
                    return $query->whereHas('courses', function (Builder $query) use ($courses) {
                        return $query->whereIn('courses.name', (array) $courses)->orWhereIn('courses.slug', (array) $courses);
                    });
                }),
                AllowedFilter::callback('completed_courses', function (Builder $query, $courses) {
                    return $query->whereHas('completions', function (Builder $query) use ($courses) {
                        return $query->whereHas('course', function (Builder $query) use ($courses) {
                            return $query->whereIn('name', (array) $courses)->orWhereIn('slug', (array) $courses);
                        });
                    });
                }),
                AllowedFilter::callback('manager', function (Builder $query, $managers) {
                    return $query->whereHas('teams', function (Builder $query) use ($managers) {
                        return $query->whereHas('owner', function (Builder $query) use ($managers) {
                            return $query->whereIn('name', (array) $managers)
                                ->orWhereIn('username', (array) $managers)
                                ->orWhereIn('email', (array) $managers);
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
            ->latest('updated_at')
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }
}
