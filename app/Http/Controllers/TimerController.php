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
  
  
 public function timers(Request $request)
  {
   if($this->timers){
       return $this->timers;
   }
   
  }
  
  public function add_timer(Request $request){
    $data = $request->all();
    
    $timer = Timer::create([
      'name' => $data['name'], 
      'law_case_id' => 0, 
      'user_id' => $this->user['id'], 
      'started_at' => new Carbon(),
     ]);
    


     return $timer->toArray();
  }
  
    public function stopRunning()
  {
   if ($timer = Timer::where('user_id', \Auth::id())->running()->first()) {
     $timer->update(['stopped_at' => new Carbon()]);
   }
  return $timer;
  }
  
  public function running()
  {
      return Timer::where('user_id', \Auth::id())->running()->first() ?? [];
  }  

 
}
