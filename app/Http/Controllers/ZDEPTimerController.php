<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Timer;
use App\LawCase;
use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
  
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      
      $this->user = \Auth::user();
      
      if(!$this->user){
				return redirect('/login');
			}
      
      $this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->timers = Timer::where('user_id', $this->user['id'])->get();

      return $next($request);
    });
  }  
  
  public function index(){
    
     

    
    return $this->timers;
      
   
  }
  
  
        public function store(Request $request, int $id)
        {
            $data = $request->validate(['name' => 'required|between:3,100']);

            $timer = LawCase::mine()->findOrFail($id)
                                    ->timers()
                                    ->save(new Timer([
                                        'name' => $data['name'],
                                        'user_id' => Auth::user()->id,
                                        'started_at' => new Carbon,
                                    ]));

            return $timer->with('project')->find($timer->id);
        }

        public function running()
        {
            return Timer::with('project')->mine()->running()->first() ?? [];
        }

        public function stopRunning()
        {
            if ($timer = Timer::mine()->running()->first()) {
                $timer->update(['stopped_at' => new Carbon]);
            }

            return $timer;
        }

 
}
