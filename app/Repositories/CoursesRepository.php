<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Http\Request;

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
        return Course::with(self::WITH)->paginate($request->per ?? 20);
    }

    public function show(Request $request)
    {
        return Course::whereSlug($request->route('course'))->with(self::WITH)->firstOrFail();
    }
}
