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

// Routes provided by korowai framework (they actually should be provided by
// the framework).
$api->version(['v1'], [
    'namespace' => 'Korowai\Framework\Http\Api\Controllers'
  ], function ($api) {
    // -- databases
    $api->group([], function($api) {
      $api->get('databases', [
        'as' => 'databases.index',
        'uses' => 'DatabaseConfigController@index'
      ]);
      $api->get('database/{id}', [
        'as' => 'database.show',
        'uses' => 'DatabaseConfigController@show'
      ]);
    });
    // --- databases

    // --- ldap entries
    $api->group([ 'middleware' => ['ldapBind'] ], function ($api) {
      $api->get('entry/{db}/{dn}', [
        'as' => 'entry.show',
        'uses' => 'EntryController@show'
      ]);
    });
    // --- ldap entries
});
