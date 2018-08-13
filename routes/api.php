<?php
use Illuminate\Http\Request;
use App\LawCase;
use App\Http\Resources\LawcaseCollection;
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


Route::group(['prefix' => 'v1'], function() {




	//Route::get('/posts/{uuid}', 'PostsController@get')->middleware('uuid.validate');

	//Route::put('/posts/{uuid}', 'PostsController@update')->middleware('uuid.validate');

        Route::resource('cases', 'Api\LawcaseController');
        
	//Route::get('articles', function() {
		// If the Content-Type and Accept headers are set to 'application/json',
		// this will return a JSON structure. This will be cleaned up later.
	//	return Article::all();
	//});

	//Route::get('articles/{id}', function($id) {
	//	return Article::find($id);
	//});

	//Route::post('articles', function(Request $request) {
	//	return Article::create($request->all);
	//});

	/*Route::put('articles/{id}', function(Request $request, $id) {
		$article = Article::findOrFail($id);
		$article->update($request->all());

		return $article;
	}); */

   /*     
	Route::delete('articles/{id}', function($id) {
		Article::find($id)->delete();

		return 204;
	});
    */
});



/*Route::group(['middleware' => ['web']], function () {    //NEED TO EVENTUALLY MOVE THIS TO API.PHP in ROUTES FOLDER

    Route::auth();
    /*
    current api routes
    these do not have dashboard prefix for urls
    */

   /* Route::get('/timers', 'TimerController@index');
    Route::post('/timers/{timer_id}/stop', 'TimerController@stopRunning');
    Route::post('/timers', 'TimerController@add_timer');
    Route::get('/timers/active', 'TimerController@running');

    /*
    * End current api routes
    */

/*});*/