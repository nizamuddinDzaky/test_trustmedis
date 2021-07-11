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

$router->group(['prefix' => 'doctor'], function () use ($router) {
    $router->post('add','DoctorController@add');
    $router->post('edit','DoctorController@edit');
    $router->get('delete/{id}','DoctorController@delete');
    $router->get('active/{id}','DoctorController@active');
    $router->get('detail/{id}','DoctorController@detail');
    $router->post('list/','DoctorController@list');
});