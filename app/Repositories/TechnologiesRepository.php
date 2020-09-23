<?php

namespace App\Repositories;

use App\Models\Technology;
use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TechnologiesRepository
{
    private const WITH = [
        'directions',
        'courses.technologies',
        'courses.level',
        'courses.completions',
        'employees.technologies',
        'employees.directions',
        'employees.teams',
        'employees.presets',
        'employees.completions',
        'employees.company'
    ];

    private const WITH_COUNT = [
        'courses',
    ];

    public function index(Request $request)
    {
        return  QueryBuilder::for(Technology::class)
            ->allowedFilters([
                'name',
                AllowedFilter::callback('courses', function (Builder $query, $courses) {
                    return $query->whereHas('courses', function (Builder $query) use ($courses) {
                        return $query->whereIn('name', (array) $courses)->orWhereIn('slug', (array) $courses);
                    });
                }),
                AllowedFilter::callback('directions', function (Builder $query, $directions) {
                    return $query->whereHas('directions', function (Builder $query) use ($directions) {
                        return $query->whereIn('name', (array) $directions)->orWhereIn('slug', (array) $directions);
                    });
                }),
                AllowedFilter::callback('employees', function (Builder $query, $employees) use ($request) {
                    if ($request->user()->isManager()) {
                        $existingEmployees = Employee::havingSpecifiedDetails((array) $employees)->get();
                        $managerHasEmployeesCheck = $existingEmployees
                            ->each(function ($employee) use ($request) {
                                return $request->user()->hasEmployee($employee);
                            });

                        throw_if(
                            $existingEmployees->isEmpty() || $managerHasEmployeesCheck === false,
                            new \LogicException("You cannot filter by not yours employees.")
                        );
                    }

                    return $query->whereHas('employees', function (Builder $query) use ($employees) {
                        return $query->havingSpecifiedDetails((array) $employees);
                    });
                }),
            ])
            ->allowedSorts([
                AllowedSort::field('name'),
                AllowedSort::field('courses_count'),
            ])
            ->with(self::WITH)
            ->withCount(self::WITH_COUNT)
            ->paginate($request->per ?? 20)
            ->appends(request()->query());
    }
}
