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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['web']], function () {    //NEED TO EVENTUALLY MOVE THIS TO API.PHP in ROUTES FOLDER
   
    Route::auth();
    /*
    current api routes
    these do not have dashboard prefix for urls
    */
  
    Route::get('/timers', 'TimerController@index');
    Route::post('/timers/{timer_id}/stop', 'TimerController@stopRunning');
    Route::post('/timers', 'TimerController@add_timer');
    Route::get('/timers/active', 'TimerController@running');   
    
    /*
    * End current api routes
    */
  
});