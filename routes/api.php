<?php

use Illuminate\Http\Request;
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

/* Auth */
Route::middleware('guest')->group(function () {
    Route::post('login', 'API\Auth\LoginController')->name('login');
    Route::post('register', 'API\Auth\RegisterController')->name('register');
});
Route::middleware('auth:api')->post('logout', 'API\Auth\LogoutController')->name('logout');
