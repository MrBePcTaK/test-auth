<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\EmployeeController;
use app\Http\Middleware\CheckCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//
Route::post('/register', [UserAuthController::class, 'register'])->name('register');
Route::post('/login', [UserAuthController::class, 'login'])->name('login');
Route::post('/refresh', [UserAuthController::class, 'refreshToken']);


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [UserAuthController::class, 'me']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
});


//
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::group(['middleware' => 'check.creator:Project'], function () {
        Route::get('/projects/{id}', [ProjectController::class, 'show']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
        // Route::apiResource('projects', ProjectController::class)->only(['show','update','destroy']);
    });

    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::group(['middleware' => 'check.creator:Room'], function () {
        Route::get('/rooms/{id}', [RoomController::class, 'show']);
        Route::put('/rooms/{id}', [RoomController::class, 'update']);
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);
    });
});

//Route::apiResource('/employee', EmployeeController::class)->middleware('auth:api');
//

// Route::resource('users', UserController::class)->only([
//     'index', 'store', 'show', 'update', 'destroy'
// ]);

