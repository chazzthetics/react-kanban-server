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
    $router->get('/columns', ['uses' => 'ColumnController@index']);
    $router->post('/boards/{uuid}/columns', ['uses' => 'ColumnController@store']);
    $router->patch('/columns/{uuid}', ['uses' => 'ColumnController@update']);
    $router->delete('/columns/{uuid}', ['uses' => 'ColumnController@destroy']);

    // Tasks
    $router->post('/columns/{uuid}/tasks', ['uses' => 'TaskController@store']);
    $router->patch('/tasks/{uuid}', ['uses' => 'TaskController@update']);
    $router->delete('/tasks/{uuid}', ['uses' => 'TaskController@destroy']);

    // Labels
    $router->get('/labels', ['uses' => 'LabelController@index']);

    // Priorities
    $router->get('/priorities', ['uses' => 'PriorityController@index']);

    // Task Labels
    $router->post('/tasks/{uuid}/labels', ['uses' => 'TaskLabelController@store']);
    $router->put('/tasks/{uuid}/labels', ['uses' => 'TaskLabelController@update']);
    $router->delete('/tasks/{uuid}/labels', ['uses' => 'TaskLabelController@destroy']);

    // Task Priority
    $router->post('/tasks/{uuid}/priority', ['uses' => 'TaskPriorityController@store']);
    $router->put('/tasks/{uuid}/priority', ['uses' => 'TaskPriorityController@update']);

    // Task Due Date
    $router->put('/tasks/{uuid}/due_date', ['uses' => 'TaskDueDateController@update']);
    $router->delete('/tasks/{uuid}/due_date', ['uses' => 'TaskDueDateController@destroy']);

    // Task Activities
    $router->get('/tasks/{uuid}/activities', ['uses' => 'TaskActivityController@index']);

    // Task Checklist
    $router->post('/tasks/{uuid}/checklist', ['uses' => 'ChecklistController@store']);
    $router->delete('/tasks/{uuid}/checklist', ['uses' => 'ChecklistController@destroy']);

    // Task Checklist Item
    $router->post('/tasks/{uuid}/checklist/items', ['uses' => 'ChecklistItemController@store']);
    $router->patch('/checklist/{uuid}', ['uses' => 'ChecklistItemController@update']);
    $router->delete('/checklist/{uuid}', ['uses' => 'ChecklistItemController@destroy']);

    // Reorder
    $router->patch('/boards/{uuid}/columns/reorder', ['uses' => 'ReorderController@columns']);
    $router->patch('/columns/{uuid}/tasks/reorder', ['uses' => 'ReorderController@tasks']);
    $router->patch('/columns/{start_uuid}/{end_uuid}/tasks/between', ['uses' => 'ReorderController@between']);

    // Move column
    $router->put('/boards/{uuid}/columns/move', ['uses' => 'MoveController']);

    // Copy column
    $router->post('/columns/{uuid}/copy', ['uses' => 'CopyColumnController']);

    // Copy Task
    $router->post('/tasks/{uuid}/copy', ['uses' => 'CopyTaskController']);

    // Activities
    $router->get('/activities', ['uses' => 'ActivityController@index']);
    $router->get('/activities/now', ['uses' => 'ActivityController@show']);
    $router->delete('/activities/{id}', ['uses' => 'ActivityController@destroy']);
    $router->put('/activities', ['uses' => 'ActivityController@clear']);

    // Photos
    $router->get('/photos', ['uses' => 'PhotoController@index']);
});
