<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\RequestController;

header('Content-type: application/json; charset=UTF-8');
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
    return view('index');
});

$router->group(['prefix' => 'source'], function () use ($router) {
    $router->get('registros',  ['uses' => 'ApiController@index']);

    $router->get('registros/{id}', ['uses' => 'ApiController@get']);

    $router->post('registros', ['uses' => 'ApiController@post']);

    $router->delete('registros/{id}', ['uses' => 'ApiController@delete']);

    $router->put('registros/{id}', ['uses' => 'ApiController@update']);
});
