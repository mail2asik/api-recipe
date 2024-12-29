<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\AuthController;

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

    // Auth Controller
    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register');
            Route::post('activate-by-url/{email}/{token}', 'activateByUrl');
            Route::post('login', 'login');
            

            Route::post('password-reminder', 'passwordReminder');
            Route::post('password-reset', 'passwordReset');


            Route::middleware('auth:sanctum')->group(function () {
                Route::post('logout', 'logout');
            });
        });
    });

});