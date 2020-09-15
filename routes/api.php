<?php

use App\Http\Controllers\EmployeeDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Auth
 *  */
Route::middleware('guest')->group(function () {
    Route::post('login', Api\Auth\LoginController::class)->name('login');
    Route::post('register', Api\Auth\RegisterController::class)->name('register');
});
Route::middleware('auth:sanctum')->post('logout', Api\Auth\LogoutController::class)->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    /*
     * Courses
     *  */
    Route::apiResource('courses', Api\CoursesController::class)->parameters(['courses' => 'course:slug']);
    Route::post('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'store'])->name('course.complete');
    Route::put('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'update'])->name('completion.update');
    Route::delete('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'destroy'])->name('course.incomplete');

    /*
     * Presets
     *  */
    Route::apiResource('presets', Api\PresetsController::class)->parameters(['presets' => 'preset:slug']);

    /*
     * Dashboards
     * */
    Route::get('dashboard/employees/{employee:username}', Api\Dashboards\EmployeeDashboardController::class)->name('dashboard.employee');
});


//GET /profiles/{user:username} - для разрабов и менеджеров
//профили пользователей с общей информацией
//
//GET /dashboard - для менеджеров
//выводится список всех команд менеджера, в рамках каждой команды выводится состав команды, у каждого юзера в составе выводятся его роадмапы, в рамках каждого роадмапа выводится список курсов, где указывается название, описание, источник и пройден ли курс этим юзером
//
//GET /dashboard/{team:slug} - для менеджеров
//выводится состав конкретной команды с роадмапами, как выше
//
//POST /presets/{preset:slug}/courses/{course:slug} - для менеджеров
//добавить указанный курс в указанный пресет
