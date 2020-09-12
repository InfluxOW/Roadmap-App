<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesRepository
{
    public function index(Request $request)
    {
        return Course::with(
            'level',
            'completions',
            'completions.employee',
            'manager',
            'manager.company'
        )->paginate($request->per ?? 20);
    }
}
