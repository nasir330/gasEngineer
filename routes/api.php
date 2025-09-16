<?php

use App\Http\Controllers\api\admin\AuthController;
use App\Http\Controllers\api\admin\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //profiles resources
    Route::resource('/profiles', ProfileController::class);

    //logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
