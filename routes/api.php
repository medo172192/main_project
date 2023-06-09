<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use  App\Http\Controllers\TestController;
use App\Http\Controllers\Api\Auth\Register\registerController;
use App\Http\Controllers\Api\Auth\Login\loginController;
use App\Http\Controllers\Api\Auth\Login\resetPasswordController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::name('api.')
    ->group(function (){
        // register Route
        Route::post('/register',[registerController::class,'Register'])
            ->name('register');

        // Login Route
        Route::post('/login',[loginController::class,'Login'])
            ->name('login');

        // Reset Password Route
        Route::post('/reset/password',[resetPasswordController::class,'ReserPassword'])
            ->name('reset.password');
    });

