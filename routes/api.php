<?php

use Illuminate\Http\Request;

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

/**
 * Authentication
 */
Route::prefix('/v1/auth')->group(function () {
    Route::post('/user', 'Api\V1\Auth\User\CreateUserController');
    Route::get('/user/{token}', 'Api\V1\Auth\User\ConfirmUserController');

    Route::post('/login', 'Api\V1\Auth\LoginController');

    Route::get('/password/{token}', 'Api\V1\Auth\Password\ConfirmPasswordController');
    Route::post('/password', 'Api\V1\Auth\Password\ForgotPasswordController');
    Route::post('/password/reset', 'Api\V1\Auth\Password\ResetPasswordController');
});

/**
 * Admin
 */
Route::prefix('/v1/admin')->group(function () {
    // Dashboard
    Route::get('/', 'Api\V1\Admin\Dashboard\DashboardController');

    // User
    Route::prefix('user')->group(function () {
        Route::get('/', 'Api\V1\Admin\User\GetUserController');
        Route::put('/', 'Api\V1\Admin\User\UpdateUserController');
    });

    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/logout', 'Api\V1\Admin\Auth\LogoutController');
        Route::post('/refresh', 'Api\V1\Admin\Auth\RefreshTokenController');
    });
});




Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'message' => trans('httpstatus.404')
    ]);
});

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
