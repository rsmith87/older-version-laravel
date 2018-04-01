<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('common/welcome');
});

Route::get('/register', function() {
  return view('auth/register');
});

Route::get('/password/reset', function() {
  return view('auth/password/reset');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
  
  Route::auth();
  
  Route::get('/logout', 'Auth\LoginController@logout');

  Route::get('/home', 'HomeController@index');
  
  Route::get('/dashboard', 'Dashboard\DashboardController@index');
  
  Route::post('/dashboard', 'AuthController@create');
  
  Route::get('/dashboard/clients', 'Dashboard\ContactController@clients');
  Route::post('/dashboard/clients/add', 'Dashboard\ContactController@add');
  
  Route::get('/dashboard/contacts', 'Dashboard\ContactController@index');
  Route::post('/dashboard/contacts/add', 'Dashboard\ContactController@add');
  
  Route::get('/dashboard/cases', 'Dashboard\CaseController@index');
  Route::post('/dashboard/cases/create', 'Dashboard\CaseController@add');

  Route::get('/dashboard/firm', 'Dashboard\FirmController@index');
  Route::post('/dashboard/firm/add', 'Dashboard\FirmController@add');
  Route::post('/dashboard/firm/user/add', 'Dashboard\FirmController@add_user');
  Route::post('/dashboard/firm/user/client/add', 'Dashboard\FirmController@add');
  
  Route::get('/dashboard/calendar', 'Dashboard\EventController@index');
  Route::post('/dashboard/calendar/event/add', 'Dashboard\EventController@add');
  
  Route::get('/dashboard/tasks', 'Dashboard\TaskController@index');
  Route::post('/dashboard/tasks/add', 'Dashboard\TaskController@add');
  Route::post('/dashboard/tasks/subtask/add', 'Dashboard\TaskController@add_subtask');
  
  Route::get('/dashboard/documents', 'Dashboard\DocumentController@index');
  Route::post('/dashboard/documents/create', 'Dashboard\DocumentController@create');
  Route::post('/dashboard/documents/upload', 'Dashboard\DocumentController@upload');
  Route::get('/dashboard/documents/delete/{name}', 'Dashboard\DocumentController@delete');
 
  Route::get('/dashboard/reports', 'Dashboard\DashboardController@reports');
  
  Route::get('/dashboard/settings', 'Dashboard\SettingController@index');
  Route::post('/dashboard/views/{type}/update', 'Dashboard\SettingController@update_view');
  
  Route::post('/dashboard/settings/theme-update', 'Dashboard\SettingController@update_theme');
  Route::post('/dashboard/settings/table-color-update', 'Dashboard\SettingController@table_color');
  Route::post('/dashboard/settings/table-size', 'Dashboard\SettingController@table_size');
   
 
  Route::group(['prefix' => 'dashboard/messages'], function () {
      Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
      Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
      Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
      Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
      Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
  }); 
  
  
});
