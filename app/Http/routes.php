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
  Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', 'Dashboard\DashboardController@index');
    Route::post('/', 'Auth\AuthController@create');

    Route::get('/clients', 'Dashboard\ContactController@clients');
    Route::get('/clients/client/{id}', 'Dashboard\ContactController@client');
    Route::post('/clients/add', 'Dashboard\ContactController@add');

    Route::get('/contacts', 'Dashboard\ContactController@index');
    Route::get('/contacts/contact/{id}', 'Dashboard\ContactController@contact');
    Route::post('/contacts/add', 'Dashboard\ContactController@add');

    Route::get('/cases', 'Dashboard\CaseController@index');
    Route::get('/cases/case/{id}', 'Dashboard\CaseController@case');
    Route::post('/cases/create', 'Dashboard\CaseController@add');
    Route::post('/cases/case/add-hours', 'Dashboard\CaseController@add_hours');

    Route::get('/firm', 'Dashboard\FirmController@index');
    Route::post('/firm/add', 'Dashboard\FirmController@add');
    Route::post('/firm/user/add', 'Dashboard\FirmController@add_user');
    Route::post('/firm/user/client/add', 'Dashboard\FirmController@create_client_login');

    Route::get('/calendar', 'Dashboard\EventController@index');
    Route::post('/calendar/event/add', 'Dashboard\EventController@add');

    Route::get('/tasks', 'Dashboard\TaskController@index');
    Route::post('/tasks/add', 'Dashboard\TaskController@add');
    Route::post('/tasks/subtask/add', 'Dashboard\TaskController@add_subtask');

    Route::get('/documents', 'Dashboard\DocumentController@index');
    Route::get('/documents/document/{id}', 'Dashboard\DocumentController@single');
    Route::post('/documents/create', 'Dashboard\DocumentController@create');
    Route::post('/documents/upload', 'Dashboard\DocumentController@upload');
    Route::get('/documents/delete/{name}', 'Dashboard\DocumentController@delete');

    Route::get('/reports', 'Dashboard\ReportController@index');

    Route::get('/invoices', 'Dashboard\InvoiceController@index');
    Route::get('/invoices/invoice/{id}', 'Dashboard\InvoiceController@invoice_view');
    Route::post('/invoices/invoice/create', 'Dashboard\InvoiceController@create');

    Route::get('/settings', 'Dashboard\SettingController@index');
    Route::post('/views/{type}/update', 'Dashboard\SettingController@update_view');
    Route::get('/settings/stripe/create', 'Dashboard\SettingController@stripe_account_create');
    Route::get('/stripe/redirect', 'Dashboard\SettingController@stripe_return');

    Route::post('/settings/theme-update', 'Dashboard\SettingController@update_theme');
    Route::post('/settings/table-color-update', 'Dashboard\SettingController@table_color');
    Route::post('/settings/table-size', 'Dashboard\SettingController@table_size');

    Route::get('/marketing', 'Dashboard\MarketingController@index');

   Route::get('/configure', 'Dashboard\DashboardController@create_roles_and_permissions');
 
  Route::group(['prefix' => 'messages'], function () {
      Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
      Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
      Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
      Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
      Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
  }); 
  });
  
});
