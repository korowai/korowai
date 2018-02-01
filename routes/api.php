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


$api = $app->make(Dingo\Api\Routing\Router::class);

// Routes provided by application
$api->version(['v1'], [
    'namespace' => 'App\Http\Api\Controllers'
  ], function ($api) {
    $api->get('example', [
      'as' => 'get.example',
      'uses' => 'ExampleController@get'
    ]);
});
