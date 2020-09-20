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

/*
 * Users Routes
 * */

Route::middleware('auth:sanctum')->group(function () {
    /*
     * Courses
     *  */
    Route::apiResource('courses', Api\CoursesController::class)->parameters(['courses' => 'course:slug'])->only('index', 'show');
    Route::post('courses/suggestions', [Api\CoursesController::class, 'suggest'])->name('courses.suggest');

    /*
     * Course Completions
     * */
    Route::middleware('employee')->group(function () {
        Route::post('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'store'])->name('courses.complete');
        Route::put('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'update'])->name('completions.update');
        Route::delete('courses/{course:slug}/completions', [Api\CourseCompletionsController::class, 'destroy'])->name('courses.incomplete');
    });

    /*
     * Presets
     *  */
    Route::apiResource('presets', Api\PresetsController::class)->parameters(['presets' => 'preset:slug']);

    /*
     * Roadmaps
     * */
    Route::get('roadmaps', [Api\RoadmapsController::class, 'index'])->name('roadmaps.index');
    Route::get('roadmaps/{employee:username}', [Api\RoadmapsController::class, 'show'])->name('roadmaps.show');
    Route::post('roadmaps', [Api\RoadmapsController::class, 'store'])->name('roadmaps.store');
    Route::delete('roadmaps/{preset:slug}/{employee:username}', [Api\RoadmapsController::class, 'destroy'])->name('roadmaps.destroy');

    /*
     * Employees
     * */
    Route::get('employees', [Api\EmployeesController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee:username}', [Api\EmployeesController::class, 'show'])->name('employees.show');

    /*
     * Managers
     * */
    Route::get('managers', [Api\ManagersController::class, 'index'])->name('managers.index');
    Route::get('managers/{manager:username}', [Api\ManagersController::class, 'show'])->name('managers.show');
});

/*
 * Admin Routes
 * */

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    /*
     * Courses
     * */
    Route::apiResource('courses', Api\Admin\CoursesController::class)->parameters(['courses' => 'course:slug'])->only('store', 'update', 'destroy');
});

//POST /presets/{preset:slug}/courses/{course:slug} - для менеджеров
//добавить указанный курс в указанный пресет
