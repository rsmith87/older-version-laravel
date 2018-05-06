<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
  return redirect('/login');;
});


Route::get('/register', function() {
  return view('auth/register');
});

Route::get('/password/reset', function() {
  return view('auth/password/reset');
});


/*
|--------------------------------------------------------------------------
| Authenticated Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['web']], function () {
  Route::get('/', function () {
    return view('welcome');
  });

  Route::auth();

  Route::get('/', function () {
    return redirect('/login');;
  });
  
  Route::get('/logout', 'Auth\LoginController@logout');

  Route::get('/home', 'HomeController@index');

  Route::get('/chat', function () {
    return view('chat');
  });
  
  Route::get('/messages', function () {
    return App\Message::with('user')->get();
  });

  Route::post('/messages', function () {
    // Store the new message
    $user = Auth::user();
    
    $message = $user->messages()->create([
      'message' => request()->get('message')
    ]);
    
    Route::post('/timer-start', 'Dashboard\DashboardController@timer');
    Route::post('/timer-pause', 'Dashboard\DashboardController@timer_pause');
    Route::get('/timer-amount', 'Dashboard\DashboardController@timer_amount');
    Route::post('/timer-stop', 'Dashboard\DashboardController@timer_stop');
    Route::post('/timer-page-change', 'Dashboard\DashboardController@timer_page');

  Route::group(['prefix' => 'dashboard'], function () {

    Route::get('/', 'Dashboard\DashboardController@index');
    Route::post('/', 'Auth\AuthController@create');
    Route::get('/profile', 'Dashboard\DashboardController@profile');
    Route::post('/profile-update', 'Dashboard\DashboardController@profile_update');

    Route::get('/clients', 'Dashboard\ContactController@clients');
    Route::get('/clients/client/{id}', 'Dashboard\ContactController@client');
    Route::post('/clients/client/note/delete', 'Dashboard\ContactController@note_delete');
    Route::post('/clients/client/note/edit', 'Dashboard\ContactController@note_edit');
    Route::post('/clients/add', 'Dashboard\ContactController@add');
    Route::post('/clients/client/{id}/log-communication', 'Dashboard\ContactController@log_communication');
    
    Route::group(['prefix' => 'contacts'], function() {   
      Route::get('/', 'Dashboard\ContactController@index');
      Route::get('/contact/{id}', 'Dashboard\ContactController@contact');
      Route::post('/add', 'Dashboard\ContactController@add');
      Route::post('/contact/notes/note/add', 'Dashboard\ContactController@note_add');
      Route::post('/contact/note/delete', 'Dashboard\ContactController@note_delete');
      Route::post('/contact/note/edit', 'Dashboard\ContactController@note_edit');   
      Route::post('/contact/log-communication', 'Dashboard\ContactController@log_communication');
    });
    
    Route::group(['prefix' => 'cases'], function() {   
      Route::get('/', 'Dashboard\CaseController@index');
      Route::get('/case/{id}', 'Dashboard\CaseController@case');
      Route::post('/create', 'Dashboard\CaseController@add');
      Route::post('/case/add-hours', 'Dashboard\CaseController@add_hours');
      Route::get('/case/{id}/timeline', 'Dashboard\CaseController@timeline');
      Route::post('/case/notes/note/add', 'Dashboard\CaseController@note_add');
      Route::post('/case/note/delete', 'Dashboard\CaseController@note_delete');
      Route::post('/case/note/edit', 'Dashboard\CaseController@note_edit');
      Route::post('/case/{id}/log-communication', 'Dashboard\CaseController@log_communication');
    });

    Route::group(['prefix' => 'firm'], function() {   
      Route::get('/', 'Dashboard\FirmController@index');
      Route::post('/add', 'Dashboard\FirmController@add');
      Route::post('/user/add', 'Dashboard\FirmController@add_user');
      Route::post('/user/client/add', 'Dashboard\FirmController@create_client_login');      
    });
    Route::group(['prefix' => 'calendar'], function() {   
      Route::get('/', 'Dashboard\EventController@index');
      Route::get('/events', 'Dashboard\EventController@client_events');
      Route::post('/event/add', 'Dashboard\EventController@add');      
      Route::post('/events/{id}/approve', 'Dashboard\EventController@approve_event');
      Route::post('/events/{id}/deny', 'Dashboard\EventController@deny_event');      
      Route::get('/events/denied', 'Dashboard\EventController@denied_events');
    });

    Route::group(['prefix' => 'tasks'], function() {    
      Route::get('/', 'Dashboard\TaskController@index');
      Route::post('/add', 'Dashboard\TaskController@add_tasklist');
      Route::post('/task/add', 'Dashboard\TaskController@add_task');
      Route::post('/task/complete-subtask/{id}/subtask', 'Dashboard\TaskController@complete_subtask');
      Route::get('/task/{id}', 'Dashboard\TaskController@view');
      Route::get('/task/{id}/view/{t_id}', 'Dashboard\TaskController@view_single_task');
      Route::post('/subtask/add', 'Dashboard\TaskController@add_subtask');
      Route::post('/subtask/category/{id}/delete', 'Dashboard\TaskController@delete_category');     
    });

    Route::group(['prefix' => 'documents'], function() {
      Route::get('/', 'Dashboard\DocumentController@index');
      Route::get('/document/{id}', 'Dashboard\DocumentController@single');
      Route::get('/document/{id}/send', 'Dashboard\DocumentController@create_download_link');
      Route::post('/create', 'Dashboard\DocumentController@create');
      Route::post('/upload', 'Dashboard\DocumentController@upload');
      Route::get('/delete/{name}', 'Dashboard\DocumentController@delete');      
    });

    Route::get('/reports', 'Dashboard\ReportController@index');

    Route::get('/invoices', 'Dashboard\InvoiceController@index');
    Route::get('/invoices/invoice/{id}', 'Dashboard\InvoiceController@invoice_view');
    Route::post('/invoices/invoice/create', 'Dashboard\InvoiceController@create');

    Route::get('/marketing', 'Dashboard\MarketingController@index');

    Route::get('/configure', 'Dashboard\DashboardController@create_roles_and_permissions');

    Route::post('/views/{type}/update', 'Dashboard\SettingController@update_view');
    
    Route::get('/stripe/redirect', 'Dashboard\SettingController@stripe_return');

    Route::group(['prefix' => 'settings'], function() {
      Route::post('/theme-update', 'Dashboard\SettingController@update_theme');
      Route::post('/table-color-update', 'Dashboard\SettingController@table_color');
      Route::post('/table-size', 'Dashboard\SettingController@table_size');   
      Route::get('/', 'Dashboard\SettingController@index'); 
      Route::post('/show-tasks-calendar', 'Dashboard\SettingController@show_tasks_calendar');
      Route::get('/roles-permissions', 'Dashboard\DashboardController@create_roles_and_permissions');
      Route::get('/stripe/create', 'Dashboard\SettingController@stripe_account_create');
      Route::get('/users', ['as' => 'users.index', 'uses' => 'Dashboard\SettingController@list_users']);
      Route::get('/users/{id}/edit', 'Dashboard\SettingController@edit_user');
      Route::post('/users/edit/{id}', ['as' => 'users.edit', 'uses' => 'Dashboard\SettingController@edit_user']);
      Route::post('/users/destroy', ['as' => 'users.destroy', 'uses' => 'Dashbaord\SettingController@destroy_user']);      
      Route::get('/roles', ['as'=>'roles.index', 'uses' => 'Dashboard\SettingController@list_roles']);
      Route::get('/roles/{id}/edit', 'Dashboard\SettingController@edit_role');
      Route::post('/roles/{id}/edit', 'Dashboard\SettingController@update_role');
      Route::get('/roles/create', 'Dashboard\SettingController@create_role');
      Route::post('/roles/create', 'Dashboard\SettingController@store_role');    
      Route::post('/roles/destroy/{id}', 'Dashboard\SettingController@destroy_role');
      Route::get('/permissions', ['as' => 'permissions.index', 'uses' => 'Dashboard\SettingController@list_permissions']);
      Route::post('/permissions/destroy/{id}', ['as' => 'permissions.destroy', 'uses' => 'Dashboard\SettingController@destroy_permission']);
      Route::get('/permissions/create', ['as' => 'permissions.create', 'uses' => 'Dashboard\SettingController@create_permission']);
      Route::post('/permissions/create', ['as' => 'permissions.create', 'uses' => 'Dashboard\SettingController@store_permission']);
    });

    Route::group(['prefix' => 'messages'], function () {
      Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
      Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);      
      Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
      Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
      Route::get('/ajax/{id}',  'MessagesController@show_ajax');
      Route::post('/store/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    }); 

  });

});  



