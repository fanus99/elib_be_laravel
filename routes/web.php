<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->post('login', ['uses' => 'LoginController@authenticate', 'as' => 'login']);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {

    $router->group(['prefix' => 'kelas'], function() use ($router)
    {
        $router->get('/', ['uses' => 'KelasController@test', 'as' => 'index']);
    });

    // $router->get('user/profile', function () {
    //     // Uses Auth Middleware
    // });
});
