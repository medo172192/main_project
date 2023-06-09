<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use  App\Http\Controllers\TestController;
use App\Http\Controllers\Api\Auth\Register\registerController;
use App\Http\Controllers\Api\Auth\Login\loginController;
use App\Http\Controllers\Api\Auth\Login\resetPasswordController;
use App\Http\Controllers\Api\Auth\Token\tokenController;
use App\Http\Controllers\Api\User\UserManagerController;
use App\Http\Controllers\Api\Auth\Logout\logoutController;
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

        // Access Token
        Route::get('/access/token',[tokenController::class,'token'])
            ->name('token');

        // Remoce Token
        Route::post('/remove/token',[tokenController::class,'removeToken'])
            ->name('remove.token');

        // Remove Token
        Route::post('/access/user/data',[UserManagerController::class,'getData'])
        ->name('user.data');

         // Remoce Token
         Route::post('/logout',[logoutController::class,'logout'])
         ->name('logout');
    });

