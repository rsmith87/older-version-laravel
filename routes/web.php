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

Route::get('/user-email-verified', 'Controller@email_verified');

Route::get('/register', function() {
  return view('auth/register');
});

Route::get('/register/payment', [
    'as' => 'addmoney.paywithstripe',
    'uses' => 'Auth\StripeController@add_payment'
    ]
);

Route::post('/register/payment',
  [
    'as' => 'addmoney.stripe',
    'uses' => 'Auth\StripeController@add_stripe_payment'
  ]
);

Route::get('/roles-permissions', 'Controller@create_roles_and_permissions');

Route::get('/password/reset', function() {
  return view('auth/password/reset');
});

Route::get('auth/{provider}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

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
      });

        Route::group(['prefix' => 'dashboard', 'middleware' => ['isVerified']], function () {
          //Route::get('decompose','\Lubusin\Decomposer\Controllers\DecomposerController@index');
          Route::get('/lock', 'Dashboard\DashboardController@lock');
          Route::post('/unlock', 'Dashboard\DashboardController@unlock');

            Route::get('/', 'Dashboard\DashboardController@index');
            //Route::post('/', 'Auth\AuthController@create');
            Route::get('/profile', 'Dashboard\DashboardController@profile');
            Route::post('/profile-update', 'Dashboard\DashboardController@profile_update');

            Route::get('/clients', 'Dashboard\ContactController@clients');
            Route::get('/clients/client/{id}', 'Dashboard\ContactController@client');
            Route::post('/clients/client/note/delete', 'Dashboard\ContactController@note_delete');
            Route::post('/clients/client/note/edit', 'Dashboard\ContactController@note_edit');
            Route::post('/clients/add', 'Dashboard\ContactController@add');
            Route::post('/clients/client/{id}/log-communication', 'Dashboard\ContactController@log_communication');

            Route::group(['prefix' => 'contacts'], function () {
                Route::get('/', 'Dashboard\ContactController@index');
                Route::get('/contact/{id}', 'Dashboard\ContactController@contact');
                Route::post('/add', 'Dashboard\ContactController@add');
                Route::post('/contact/delete', 'Dashboard\ContactController@delete');
                Route::post('/contact/notes/note/add', 'Dashboard\ContactController@note_add');
                Route::post('/contact/note/delete', 'Dashboard\ContactController@note_delete');
                Route::post('/contact/note/edit', 'Dashboard\ContactController@note_edit');
                Route::post('/contact/log-communication', 'Dashboard\ContactController@log_communication');
                Route::Post('/contact/relate', 'Dashboard\ContactController@relate');
            });

            Route::group(['prefix' => 'cases'], function () {
                Route::get('/', 'Dashboard\CaseController@index');
                Route::get('/case/{id}', 'Dashboard\CaseController@case');
                Route::post('/create', 'Dashboard\CaseController@add');
                Route::post('/case/add-hours', 'Dashboard\CaseController@add_hours');
                Route::post('/case/reference', 'Dashboard\CaseController@reference_client');
                Route::get('/case/{id}/timeline', 'Dashboard\CaseController@timeline');
                Route::post('/case/notes/note/add', 'Dashboard\CaseController@note_add');
                Route::post('/case/note/delete', 'Dashboard\CaseController@note_delete');
                Route::post('/case/note/edit', 'Dashboard\CaseController@note_edit');
                Route::post('/case/{id}/log-communication', 'Dashboard\CaseController@log_communication');
                Route::post('/case/delete', 'Dashboard\CaseController@delete');


                Route::get('/timers_cases', 'Dashboard\CaseController@timer_cases');
                Route::get('/timers_cases/active', 'Dashboard\CaseController@timers_active');
                Route::post('/case/{id}/timers', 'Dashboard\CaseController@timer_store');
                Route::post('/{id}/timers/stop', 'Dashboard\CaseController@stop_timer');

            });
Route::get('auth/{provider}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
            Route::group(['prefix' => 'firm'], function () {
                Route::get('/', 'Dashboard\FirmController@index');
                Route::post('/add', 'Dashboard\FirmController@add');
                Route::post('/user/add', 'Dashboard\FirmController@add_user');
                Route::post('/user/client/add', 'Dashboard\FirmController@create_client_login');
            });
            Route::group(['prefix' => 'calendar'], function () {
                Route::get('/', 'Dashboard\EventController@index');
                Route::post('/drop-event', 'Dashboard\EventController@drop_event');
                Route::post('/modify-event', 'Dashboard\EventController@modify_event');
                Route::post('/extend-event', 'Dashboard\EventController@extend_event');
                Route::get('/events', 'Dashboard\EventController@client_events');
                Route::post('/event/add', 'Dashboard\EventController@add');
                Route::post('/events/{id}/approve', 'Dashboard\EventController@approve_event');
                Route::post('/events/{id}/deny', 'Dashboard\EventController@deny_event');
                Route::get('/events/denied', 'Dashboard\EventController@denied_events');
            });




            Route::group(['prefix' => 'tasks'], function () {
                Route::get('/', 'Dashboard\TaskController@index');
                Route::post('/add', 'Dashboard\TaskController@add_tasklist');
                Route::post('/task/add', 'Dashboard\TaskController@add_task');
                Route::post('/task/{name}/complete', 'Dashboard\TaskController@complete_task');
                Route::post('/task/subtask/{id}/complete', 'Dashboard\TaskController@complete_subtask');
                Route::get('/task/{id}', 'Dashboard\TaskController@view');
                Route::get('/task/{id}/view/{t_id}', 'Dashboard\TaskController@view_single_task');
                Route::post('/delete-task', 'Dashboard\TaskController@delete');
                Route::post('/subtask/add', 'Dashboard\TaskController@add_subtask');
                Route::post('/subtask/delete', 'Dashboard\TaskController@delete_subtask');
                Route::post('/subtask/category/{id}/delete', 'Dashboard\TaskController@delete_category');
            });

            Route::group(['prefix' => 'mail'], function () {
              Route::get('/', 'Dashboard\MailController@index');
            });

            Route::group(['prefix' => 'documents'], function () {
                Route::get('/', 'Dashboard\DocumentController@index');
                Route::get('/document/{id}', 'Dashboard\DocumentController@single');
                Route::get('/document/{id}/send', 'Dashboard\DocumentController@create_download_link');
                Route::post('/create', 'Dashboard\DocumentController@create');
                Route::post('/upload', 'Dashboard\DocumentController@upload');
                Route::get('/delete/{name}', 'Dashboard\DocumentController@delete');
            });


            Route::group(['prefix' => 'reports'], function () {
              Route::get('/', 'Dashboard\ReportController@index');
              Route::get('/cases', 'Dashboard\ReportController@cases');
              Route::get('/payments', 'Dashboard\ReportController@payments');
              Route::get('/hours', 'Dashboard\ReportController@hours');
            });


            Route::get('/invoices', 'Dashboard\InvoiceController@index');
            Route::get('/invoices/invoice/{id}', 'Dashboard\InvoiceController@invoice_view');
            Route::post('/invoices/invoice/create', 'Dashboard\InvoiceController@create');

            Route::get('/marketing', 'Dashboard\MarketingController@index');

            Route::get('/configure', 'Dashboard\DashboardController@create_roles_and_permissions');

            Route::post('/views/{type}/update', 'Dashboard\SettingController@update_view');

            Route::get('/stripe/redirect', 'Dashboard\SettingController@stripe_return');

            Route::group(['prefix' => 'settings'], function () {
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
                Route::post('/users/destroy', ['as' => 'users.destroy', 'uses' => 'Dashboard\SettingController@destroy_user']);
                Route::get('/roles', ['as' => 'roles.index', 'uses' => 'Dashboard\SettingController@list_roles']);
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

            //ctf0\MediaManager\MediaRoutes::routes();

            Route::group(['prefix' => 'messages'], function () {
                Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
                Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
                Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
                Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
                Route::get('/ajax/{id}', 'MessagesController@show_ajax');
                Route::post('/store/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
            });

        });







        $middleware = array_merge(\Config::get('lfm.middlewares'), [
            '\App\Http\Middleware\MultiUser',
            '\App\Http\Middleware\CreateDefaultFolder',
        ]);
        $prefix = \Config::get('lfm.url_prefix', \Config::get('lfm.prefix', 'laravel-filemanager'));
        $as = 'unisharp.lfm.';
        $namespace = 'Media';

        // make sure authenticated
        Route::group(compact('middleware', 'prefix', 'as', 'namespace'), function () {

            // Show LFM
            Route::get('/', [
                'uses' => 'LfmController@show',
                'as' => 'show',
            ]);

            // Show integration error messages
            Route::get('/errors', [
                'uses' => 'LfmController@getErrors',
                'as' => 'getErrors',
            ]);

            // upload
            Route::any('/upload', [
                'uses' => 'UploadController@upload',
                'as' => 'upload',
            ]);

            // list images & files
            Route::get('/jsonitems', [
                'uses' => 'ItemsController@getItems',
                'as' => 'getItems',
            ]);

            // folders
            Route::get('/newfolder', [
                'uses' => 'FolderController@getAddfolder',
                'as' => 'getAddfolder',
            ]);
            Route::get('/deletefolder', [
                'uses' => 'FolderController@getDeletefolder',
                'as' => 'getDeletefolder',
            ]);
            Route::get('/folders', [
                'uses' => 'FolderController@getFolders',
                'as' => 'getFolders',
            ]);

            // crop
            Route::get('/crop', [
                'uses' => 'CropController@getCrop',
                'as' => 'getCrop',
            ]);
            Route::get('/cropimage', [
                'uses' => 'CropController@getCropimage',
                'as' => 'getCropimage',
            ]);
            Route::get('/cropnewimage', [
                'uses' => 'CropController@getNewCropimage',
                'as' => 'getCropimage',
            ]);

            // rename
            Route::get('/rename', [
                'uses' => 'RenameController@getRename',
                'as' => 'getRename',
            ]);

            // scale/resize
            Route::get('/resize', [
                'uses' => 'ResizeController@getResize',
                'as' => 'getResize',
            ]);
            Route::get('/doresize', [
                'uses' => 'ResizeController@performResize',
                'as' => 'performResize',
            ]);

            // download
            Route::get('/download', [
                'uses' => 'DownloadController@getDownload',
                'as' => 'getDownload',
            ]);

            // delete
            Route::get('/delete', [
                'uses' => 'DeleteController@getDelete',
                'as' => 'getDelete',
            ]);

            // Route::get('/demo', 'DemoController@index');
        });

        Route::group(compact('prefix', 'as', 'namespace'), function () {
            // Get file when base_directory isn't public
            $images_url = '/' . \Config::get('lfm.images_folder_name') . '/{base_path}/{image_name}';
            $files_url = '/' . \Config::get('lfm.files_folder_name') . '/{base_path}/{file_name}';
            Route::get($images_url, 'RedirectController@getImage')
                ->where('image_name', '.*');
            Route::get($files_url, 'RedirectController@getFile')
                ->where('file_name', '.*');
        });




    });





// MediaManager

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
