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

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    // Boards
    $router->get('/boards', ['uses' => 'BoardController@index']);
    $router->post('/boards', ['uses' => 'BoardController@store']);
    $router->patch('/boards/{uuid}', ['uses' => 'BoardController@update']);
    $router->delete('/boards/{uuid}', ['uses' => 'BoardController@destroy']);

    // Columns
    // $router->get('/boards/{uuid}/columns', ['uses' => 'ColumnController@index']);
    $router->get('/columns', ['uses' => 'ColumnController@index']);
    $router->post('/boards/{uuid}/columns', ['uses' => 'ColumnController@store']);
    $router->patch('/columns/{uuid}', ['uses' => 'ColumnController@update']);
    $router->delete('/columns/{uuid}', ['uses' => 'ColumnController@destroy']);

    // Tasks
    $router->post('/columns/{uuid}/tasks', ['uses' => 'TaskController@store']);
    $router->delete('/tasks/{uuid}', ['uses' => 'TaskController@destroy']);

    // Reorder
    $router->patch('/boards/{uuid}/columns/reorder', ['uses' => 'ReorderController@columns']);
});
