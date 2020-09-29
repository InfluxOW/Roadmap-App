<?php

use App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
    Route::get('users', function () {
        return User::all();
    });
    Route::post('login', Api\Auth\LoginController::class)->name('login');
    Route::post('register', Api\Auth\RegisterController::class)->name('register');
});
Route::middleware('auth:sanctum')->post('logout', Api\Auth\LogoutController::class)->name('logout');

/*
 * Users Routes
 * */

Route::middleware('auth:sanctum')->group(function () {
    /*
     * Companies
     * */
    Route::apiResource('companies', Api\CompaniesController::class)->parameters(['companies' => 'company:slug'])->only('index', 'show');

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
    Route::post('presets/generation', [Api\PresetsGenerationController::class, 'store'])->name('presets.generate');
    Route::post('presets/{preset:slug}/courses', [Api\CourseAssignmentsController::class, 'store'])->name('presets.courses.assign');
    Route::delete('presets/{preset:slug}/courses/{course:slug}', [Api\CourseAssignmentsController::class, 'destroy'])->name('presets.courses.unassign');

    /*
     * Roadmaps
     * */
    Route::get('roadmaps', [Api\RoadmapsController::class, 'index'])->name('roadmaps.index');
    Route::get('roadmaps/{employee:username}', [Api\RoadmapsController::class, 'show'])->name('roadmaps.show');
    Route::post('roadmaps', [Api\RoadmapsController::class, 'store'])->name('roadmaps.store');
    Route::delete('roadmaps/{preset:slug}/{employee:username}', [Api\RoadmapsController::class, 'destroy'])->name('roadmaps.destroy');

    /*
     * Profiles
     * */
    Route::apiResource('profiles', Api\ProfilesController::class)->parameters(['profiles' => 'user:username'])->only('index', 'show');

    /*
     * Technologies
     * */
    Route::get('technologies', [Api\TechnologiesController::class, 'index'])->name('technologies.index');

    /*
     * Invites
     * */
    Route::post('invites', [Api\InvitesController::class, 'store'])->name('invites.store');
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
