<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\RedirectIfAdminAuthenticated;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\RecipeController;

Route::group(['domain' => config('constants.domain_admin')], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('logout', 'logout');
    });

    Route::middleware(RedirectIfAdminAuthenticated::class)->group(function () {

        Route::controller(AuthController::class)->group(function () {
            Route::get('/', 'login');

            Route::get('login', 'login');
            Route::post('doLogin', 'doLogin');

            Route::get('forgot-password', 'forgotPassword');
            Route::post('doForgotPassword', 'doForgotPassword');

            Route::get('reset-password', 'resetPassword');
            Route::post('doResetPassword', 'doResetPassword');
        });

    });

    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('dashboard', 'index');
        });

        Route::controller(UserController::class)->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', 'index');
                Route::get('view/{user_uid}', 'view');
                Route::get('suspend/{user_uid}', 'suspend');
                Route::get('approve/{user_uid}', 'approve');
            });
        });

        Route::controller(RecipeController::class)->group(function () {
            Route::prefix('recipe')->group(function () {
                Route::get('/', 'index');
                Route::get('view/{recipe_uid}', 'view');
                Route::get('reject/{recipe_uid}', 'reject');
                Route::get('approve/{recipe_uid}', 'approve');
            });
        });

        Route::controller(CacheController::class)->group(function () {
            Route::prefix('cache')->group(function () {
                Route::get('/', 'index');
                Route::get('clear', 'clear');
            });
        });

        Route::controller(AuthController::class)->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('/', 'getProfile');
                Route::post('/', 'postProfile');
            });
            
            Route::get('change-password', 'changePassword');
            Route::post('doChangePassword', 'doChangePassword');
        });

    });
});

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});