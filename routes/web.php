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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'auth'], function() use ($router)
{
    $router->post('login', ['uses' => 'AuthController@authenticate', 'as' => 'login']);
    $router->post('register', ['uses' => 'AuthController@register', 'as' => 'register']);
    $router->post('refresh-token', ['uses' => 'AuthController@refreshToken', 'as' => 'refresh-token']);
});


$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->group(['prefix' => 'master'], function() use ($router)
    {
        $router->group(['prefix' => 'kelas'], function() use ($router)
        {
            $router->get('/', ['uses' => 'KelasController@GetAll', 'as' => 'GetAll']);
            $router->get('/{id}', ['uses' => 'KelasController@GetById', 'as' => 'GetById']);
            $router->post('/', ['uses' => 'KelasController@create', 'as' => 'create']);
            $router->put('/{id}', ['uses' => 'KelasController@update', 'as' => 'update']);
            $router->delete('/{id}', ['uses' => 'KelasController@delete', 'as' => 'delete']);
        });

        $router->group(['prefix' => 'siswa'], function() use ($router)
        {
            $router->get('/', ['uses' => 'SiswaController@GetAll', 'as' => 'GetAll']);
            $router->get('/{id}', ['uses' => 'SiswaController@GetById', 'as' => 'GetById']);
            $router->post('/', ['uses' => 'SiswaController@create', 'as' => 'create']);
            $router->put('/{id}', ['uses' => 'SiswaController@update', 'as' => 'update']);
            $router->delete('/{id}', ['uses' => 'SiswaController@delete', 'as' => 'delete']);
        });

        $router->group(['prefix' => 'semester'], function() use ($router)
        {
            $router->get('/', ['uses' => 'SemesterController@GetAll', 'as' => 'GetAll']);
            $router->get('/{id}', ['uses' => 'SemesterController@GetById', 'as' => 'GetById']);
            $router->post('/', ['uses' => 'SemesterController@create', 'as' => 'create']);
            $router->put('/{id}', ['uses' => 'SemesterController@update', 'as' => 'update']);
            $router->delete('/{id}', ['uses' => 'SemesterController@delete', 'as' => 'delete']);
        });
    });


});
