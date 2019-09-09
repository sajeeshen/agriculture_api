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
    return $router->app->version();
});



$router->group(['prefix' => 'api', 'middleware' => 'api.auth'], function () use ($router) {
    $router->get('/fields', 'FieldController@index');
    $router->get('/field/{slug}', 'FieldController@show');
    $router->get('/tractors', 'TractorController@index');
    $router->get('/tractor/{slug}', 'TractorController@show');
    $router->get('/users', 'UserManageController@index');
    $router->get('/reports/list', 'ReportManageController@index');
    $router->post('/report/add', 'ReportManageController@store');
    $router->put('/report/update/{slug}', 'ReportManageController@update');
    $router->patch('/report/update/{slug}', 'ReportManageController@update');
    $router->get('/report/show/{slug}', 'ReportManageController@show');
    $router->get('/reports/view', 'ReportManageController@report');



});

$router->group(['prefix' => 'api' , 'middleware' => 'api.auth:Admin,Supervisor'], function () use ($router) {
    $router->post('/field/add', 'FieldController@store');
    $router->put('/field/update/{slug}', 'FieldController@update');
    $router->delete('/field/delete/{slug}', 'FieldController@destroy');

    $router->post('/tractor/add', 'TractorController@store');
    $router->put('/tractor/update/{slug}', 'TractorController@update');
    $router->patch('/tractor/update/{slug}', 'TractorController@update');
    $router->delete('/tractor/delete/{slug}', 'TractorController@destroy');
    $router->delete('/report/delete/{slug}', 'ReportManageController@destroy');
    $router->post('/report/approve/{slug}', 'ReportManageController@approve');


});

$router->group(['prefix' => 'api' , 'middleware' => 'api.auth:Admin'], function () use ($router) {
   
    $router->post('/user/add', 'UserManageController@store');
    $router->get('/user/list', 'UserManageController@index');

    $router->get('/user/view/{slug}', 'UserManageController@show');
    $router->put('/user/update/{slug}', 'UserManageController@update');
    $router->patch('/user/update/{slug}', 'UserManageController@update');
    $router->delete('/user/delete/{slug}', 'UserManageController@destroy');

});



$router->post(
    'auth/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);


// $router->post('/login', 'AuthenticateController@authenticate');
