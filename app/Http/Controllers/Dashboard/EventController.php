<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Settings;
use App\Event;
use Carbon;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->user = \Auth::user();
      $this->settings = Settings::where('user_id', \Auth::id())->first();    
  } 
  
  public function index(Request $request)
  {
    $request->user()->authorizeRoles(['auth_user', 'administrator']);
    $not_allowed = $request->user()->hasRole('auth_user');  
    
    $events = Event::where('u_id', $this->user->id)->get();
    
    return view('dashboard/calendar', ['user_name' => $this->user['name'], 'events' => $events, 'theme' => $this->settings->theme, 'role' => $not_allowed]);
  }
  
  private function fix_date($dts, $dte)
  {
    $d = Carbon\Carbon::parse($dts)->format('Y-m-d');
    $dt = Carbon\Carbon::parse($d. " " . $dte.":00", 'America/Chicago')->format('Y-m-d H:i:s');
    return $dt;
    
  }
  
  public function add(Request $request)
  {
    $data = $request->all();
    
    $event = Event::updateOrCreate([
      'id' => !empty($data['id']) ? $data['id'] : "",
    ],
    [
      'name' => $data['name'],
      'description' => $data['description'],
      'start_date' => $this->fix_date($data['start_date'], $data['start_time']),
      'end_date' => $this->fix_date($data['end_date'], $data['end_time']),
      'start_time' => $data['start_time'],
      'end_time' => $data['end_time'],
      'u_id' => $this->user->id,
      'co_id' => !empty($data['co_id']) ? $data['co_id'] : "",
      'c_id' => !empty($data['c_id']) ? $data['c_id'] : "",
      'f_id' => $this->settings->firm_id,
    ]);
    
    if(!empty($data['id'])){
      $status = 'edited';
    }
    else {
      $status = 'added';
    }
    
    return redirect('/dashboard/calendar')->with('status', 'Event '.$status.' successfully!'); 
  }
}
