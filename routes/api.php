<?php

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

/* Auth */
Route::middleware('guest')->group(function () {
    Route::post('login', Api\Auth\LoginController::class)->name('login');
    Route::post('register', Api\Auth\RegisterController::class)->name('register');
});
Route::middleware('auth:api')->post('logout', Api\Auth\LogoutController::class)->name('logout');

//GET /profiles/{user:username} - для разрабов и менеджеров
//профили пользователей с общей информацией
//
//GET /dashboard - для менеджеров
//выводится список всех команд менеджера, в рамках каждой команды выводится состав команды, у каждого юзера в составе выводятся его роадмапы, в рамках каждого роадмапа выводится список курсов, где указывается название, описание, источник и пройден ли курс этим юзером
//
//GET /dashboard/{user:username} - для разрабов и менеджеров
//выводится список роадмапов разработчика с курсами, как выше
//
//GET /dashboard/{team:slug} - для менеджеров
//выводится состав конкретной команды с роадмапами, как выше
//
//POST /courses/{course:slug}/complete - для разрабов
//отметить курс как завершённый
//
//POST /courses/{course:slug}/incomplete - для разрабов
//отметить курс как незавершённый
//
//GET /presets - для менеджеров
//выводится список пресетов
//
//GET /presets/{preset:slug} - для менеджеров
//выводится список курсов данного пресета, а также список разработчиков, которым он назначен
//
//POST /presets - для менеджеров
//создать новый пресет
//
//GET /courses - для менеджеров
//выводится список курсов
//
//GET /courses/{course:slug} - для менеджеров
//выводится информация о конкретном курсе, а также список разработчиков, которые его завершили
//
//POST /courses - для менеджеров
//создать новый курс
//
//POST /presets/{preset:slug}/courses/{course:slug} - для менеджеров
//добавить указанный курс в указанный пресет
