<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\ProfessionalsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CourseController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UsersController::class, 'authProfille']);
    Route::get('/home/{vacancy]', [HomeController::class, 'show']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);
    Route::get('/users/{user}/{type?}', [UsersController::class, 'show']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'index']);
});
Route::post('/users', [UsersController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
