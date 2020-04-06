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

$router->group(['prefix' => 'api/auth'], function () use ($router) {
    $router->post('/login', ['uses' => 'AuthController@login']);
    $router->post('/register', ['uses' => 'AuthController@register']);
    $router->post('/refresh', ['uses' => 'AuthController@refresh']);
    $router->post('/logout', ['uses' => 'AuthController@logout']);
    $router->post('/user', ['uses' => 'AuthController@user']);
});
