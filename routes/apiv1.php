<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['Api' => $router->app->version()]);
    //return $router->app->version();
});

/**
 *  Common (public) routes
 */
$router->group(['prefix' => '/v1/common'], function () use ($router) {
    $router->post('contact/', 'Api\V1\Common\ContactFormController');
});

/**
 * Auth routes
 */
$router->group(['prefix' => '/v1/auth'], function () use ($router) {
    $router->post('/register', 'Api\V1\Auth\Register\CreateUserController');
    $router->get('/register/{token}', 'Api\V1\Auth\Register\ConfirmUserController');

    $router->post('/login', 'Api\V1\Auth\LoginController');
    $router->get('/logout', 'Api\V1\Auth\LogoutController');

    $router->get('/password/{token}', 'Api\V1\Auth\Password\ConfirmPasswordController');
    $router->post('/password', 'Api\V1\Auth\Password\ForgotPasswordController');
    $router->post('/password/reset', 'Api\V1\Auth\Password\ResetPasswordController');
});

/**
 * Admin (private) routes
 */
$router->group(['prefix' => '/v1/admin'], function () use ($router) {
   // Dashboard
   $router->get('/', 'Api\V1\Admin\Dashboard\DashboardController');

   // User
   $router->get('/user', 'Api\V1\Admin\User\ReadUserController');
   $router->put('/user', 'Api\V1\Admin\User\UpdateUserController');
   $router->get('/user/logout', 'Api\V1\Admin\User\LogoutUserController');
   $router->post('/user/refresh', 'Api\V1\Admin\User\RefreshUserTokenController');

   // Settings
   $router->get('/settings', 'Api\V1\Admin\Settings\ReadSettingsController');
   $router->put('/settings', 'Api\V1\Admin\Settings\UpdateSettingsController');
});

