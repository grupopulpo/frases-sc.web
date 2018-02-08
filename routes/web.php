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
	$router->post('/v1/api-create-user','MainController@createUser');

$router->group(['middleware'=>'auth:api'],function() use($router){
	$router->post('/v1/api-update-user','MainController@updateUser');
	
	$router->get('/v1/api-background/{id}','MainController@getBackground');
	$router->post('/v1/api-background-save','MainController@saveBackground');

	$router->post('/v1/api-phrases-get/{type}','MainController@getPhrases');
	$router->post('/v1/api-phrases-save','MainController@savePhrase');
	$router->get('/v1/api-phrase-postal/{id}','MainController@getPhrase');
	$router->post('/v1/api-phrase-approved/{id}','MainController@approvedPhrase');

});