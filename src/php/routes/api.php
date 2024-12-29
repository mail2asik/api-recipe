<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

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

Route::group(['domain' => config('constants.domain_api')], function () {

    Route::controller(PingController::class)->group(function () {
        Route::get('/ping', 'index');
    });

    Route::get('/test-redis-publish', function () {
        Redis::publish('test-redis-channel', json_encode([
            'name' => 'Hello World'
        ]));

        return ['Laravel' => app()->version()];
    });

    // Auth Controller
    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register');
            Route::post('activate-by-url/{email}/{token}', 'activateByUrl');
            Route::post('login', 'login');
            
            Route::post('password-reminder', 'passwordReminder');
            Route::post('password-reset', 'passwordReset');

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('password-change', 'passwordChange');

                Route::post('refresh-token', 'refreshToken');
                Route::post('logout', 'logout');
            });
        });
    });

    // User Controller
    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/', 'show');
            Route::put('/', 'update');
        });
    });

});