<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/tasks', 'TaskController@index');
// Route::get('/tasks/{task}', 'TaskController@show');
// Route::post('/tasks', 'TaskController@store');
// Route::patch('/tasks/{task}', 'TaskController@update');
// Route::delete('/tasks/{task}', 'TaskController@destroy');

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@create');

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('tasks', 'TaskController');
    Route::post('/tasks/complete', 'CompleteTaskController@store');
    Route::post('/tasks/complete/{task}', 'CompleteTaskController@destroy');

    Route::apiResource('folders', 'FolderController');
    Route::apiResource('users', 'UserController')->except(['index', 'store']); // Register takes care of storing a user...
});
