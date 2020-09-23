<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\EmployeeLevel;
use App\Models\UserTypes\Employee;
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
                        return $query->whereIn('name', (array) $levels)->orWhereIn('slug', (array) $levels);
                    });
                }),
                AllowedFilter::callback('technologies', function (Builder $query, $technologies) {
                    return $query->whereHas('technologies', function (Builder $query) use ($technologies) {
                        return $query->whereIn('name', (array) $technologies)->orWhereIn('slug', (array) $technologies);
                    });
                }),
                AllowedFilter::callback('presets', function (Builder $query, $presets) {
                    return $query->whereHas('presets', function (Builder $query) use ($presets) {
                        return $query->whereIn('name', (array) $presets)->orWhereIn('slug', (array) $presets);
                    });
                }),
                AllowedFilter::callback('completed_by', function (Builder $query, $employees) use ($request) {
                    if ($request->user()->isManager()) {
                        $existingEmployees = Employee::havingSpecifiedDetails((array) $employees)->get();
                        $managerHasEmployeesCheck = $existingEmployees
                            ->every(function ($employee) use ($request) {
                                return $request->user()->hasEmployee($employee);
                            });

                        throw_if(
                            $existingEmployees->isEmpty() || $managerHasEmployeesCheck === false,
                            new \LogicException("You cannot filter by not yours employees.")
                        );
                    }

                    return $query->whereHas('completions', function (Builder $query) use ($employees) {
                        return $query->whereHas('employee', function (Builder $query) use ($employees) {
                              return $query->havingSpecifiedDetails((array) $employees);
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

    public function store(Request $request)
    {
        $course = Course::make($request->only('name', 'source', 'description'));
        $course->level()->associate(EmployeeLevel::whereSlug($request->level)->first());
        $course->save();

        return $course;
    }

    public function update(Request $request)
    {
        $course = $request->route('course');

        $course->update($request->only('name', 'source', 'description'));
        $course->level()->associate(EmployeeLevel::whereSlug($request->level)->first());
        $course->save();

        return $course;
    }
}
