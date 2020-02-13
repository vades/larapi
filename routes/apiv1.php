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
 * Abort all requests without Accept: application/json in the header
 */
/* if (!request()->wantsJson()) {
   abort(400,'ABORT: Missing Accept: application/json in the header');
} */

/**
 * Authentication
 */
Route::prefix('/auth')->group(function () {
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
Route::prefix('/admin')->group(function () {
    // Dashboard
    Route::get('/', 'Api\V1\Admin\Dashboard\DashboardController');

    // User
    Route::get('/user', 'Api\V1\Admin\User\ReadUserController');
    Route::put('/user', 'Api\V1\Admin\User\UpdateUserController');

    // Settings
    Route::get('/settings', 'Api\V1\Admin\Settings\ReadSettingsController');
    Route::put('/settings', 'Api\V1\Admin\Settings\UpdateSettingsController');

    // Auth
    Route::get('/auth/logout', 'Api\V1\Admin\Auth\LogoutController');
    Route::post('/auth/refresh', 'Api\V1\Admin\Auth\RefreshTokenController');
});

/**
 * Public
 */
Route::prefix('/common')->group(function () {

    // Subscribe
    Route::post('/subscribe', 'Api\V1\Common\Subscribtion\CreateSubscribtionController');

    // Email
    Route::post('/email', 'Api\V1\Common\Email\SendEmailController');
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
