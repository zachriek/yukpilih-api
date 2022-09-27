<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PollController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/reset_password', [AuthController::class, 'resetPassword']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/poll', [PollController::class, 'store']);
    Route::get('/poll', [PollController::class, 'index']);
    Route::get('/poll/{poll}', [PollController::class, 'show']);
    Route::post('/poll/{poll}/vote/{choice}', [PollController::class, 'vote']);
    Route::delete('/poll/{poll}', [PollController::class, 'destroy']);
});