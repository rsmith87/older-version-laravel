<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Notifications;
use App\Http\Requests;
use App\Settings;
use App\Contact;
use App\LawCase;
use App\User;
use App\Event;
use App\Task;
use Carbon;
use Mail;
use Illuminate\Notifications\Notification;
use App\Notifications\EventConfirmNotification;
use App\Notifications\EventDenyNotification;
use App\Http\Controllers\Controller;

class EventController extends Controller
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
			if(!$this->user->hasPermissionTo('view calendar')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}		            
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			return $next($request);
		});
	} 

	public function index(Request $request)
	{
		//users events
    if($this->settings->task_calendar){
      $tasks_events = Task::where('user_id', $this->user['id'])->get();
    } else {
      $tasks_events = null;
    }
      
		$events = Event::where(['u_id' => $this->user->id])->with('user')->with('contact')->get();
		$contact = null;
		//clients accessing lawyer events
		if($this->user->hasRole('client')){
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$events = Event::where(['u_id' => $contact->user_id, 'approved' => 1])->with('user')->get();
		}


		return view('dashboard/calendar', [
			'user_name' => $this->user['name'], 
			'events' => $events, 
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'user' => $contact,
      'show_task_calendar' => $tasks_events,
		]);
	}

	public function client_events()
	{
		//0 is not seen by lawyer
		//1 is seen by lawyer and approved
		//2 is seen by lawyer and not approved
		
		$events = Event::where(['u_id' => $this->user['id'], 'approved' => 0])->with('contact')->get();

		return view('dashboard/events', [
			'user_name' => $this->user['name'], 
			'events' => $events, 
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'title' => 'Client appointment requests',

		]); 
	}
	
	public function add(Request $request)
	{
		$data = $request->all();
		if(!empty($data['id'])){
			$status = 'edited';
		}
		else {
			$status = 'added';
		}		
		
		$start_date = new \DateTime($this->fix_date($data['start_date'], $data['start_time']));
		$end_date = new \DateTime($this->fix_date($data['end_date'], $data['end_time']));
		
		$events = Event::where(['u_id' => $this->user['id'], 'approved' => 1])->get();
		//clients accessing lawyer events
		if($this->user->hasRole('client')){
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$events = Event::where(['u_id' => $contact->user_id, 'approved' => 1])->get();
		}
				
		foreach($events as $event){
			
			$event_start_date = new \DateTime($event['start_date']);
			$event_end_date = new \DateTIme($event['end_date']);
			//print_r("Start date from form: " . $start_date->format('Y-m-d H:i:s') . " Start date from event: " . $event_start_date->format('Y-m-d H:i:s') . " End date from form: " . $end_date->format('Y-m-d H:i:s') . " End date from event: " . $event_end_date->format('Y-m-d H:i:s'));

			if(($start_date > $event_start_date && $start_date < $event_end_date) || ($end_date < $event_end_date && $end_date > $event_start_date))
				return redirect('/dashboard/calendar')->withErrors(['There is an existing appointment in the time selected.  Please choose another time.']);
			}
			
		//clients accessing lawyer events
		$u_id = $this->user['id'];
		$approved = 1;
		$message = 'Event '.$status.' successfully!';
		
		if($this->user->hasRole('client')){
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$u_id = $contact->user_id;
			$approved = 0;
			$data['co_id'] = $contact->id;
			$data['c_id'] = $contact->case_id;
			$message = 'Appointment request submitted.  If approved, you will receive an email letting you know it was approved or denied.';

		}
		
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
			'approved' => $approved,
			'u_id' => $u_id,
			'co_id' => isset($data['co_id']) ? $data['co_id'] : "",
			'c_id' => isset($data['c_id']) ? $data['c_id'] : "",
			'f_id' => $this->settings->firm_id,
		]);

		


		return redirect('/dashboard/calendar')->with('status', $message); 
	}
	
	public function denied_events()
	{
		//users events
		$events = Event::where(['u_id' => $this->user->id, 'approved' => 2])->get();
		
		//clients accessing lawyer events
		if($this->user->hasRole('client')){
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$events = Event::where(['u_id' => $contact->user_id, 'approved' => 2])->get();
		}
		
		return view('dashboard/events', [
			'user_name' => $this->user['name'], 
			'events' => $events, 
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'title' => 'Denied events',
		]); 		
	}
	
	public function approve_event($id)
	{
		$event = Event::where('id', $id)->first();
		//print_r($event);
		if(!empty($event)){
			Event::where('id', $id)->update(['approved' => 1]);
			$contact = Contact::where('id', $event->co_id)->first();
			$client_user = User::where('id', $contact->has_login)->first();
			$client_user->sendEventConfirmNotification($client_user);


			return redirect('/dashboard/calendar/events')->with('status', 'Event '.$event->name.' approved and is now on your calendar!');
		} else {
			return redirect('/dashboard/calendar/events/')->withErrors(['Could not find event']);
		}
	}
	
	public function deny_event($id)
	{
		$event = Event::where('id', $id)->first();
		if(!empty($event)){	
			Event::where('id', $id)->update(['approved' => 2]);
			$contact = Contact::where('id', $event->co_id)->first();
			$client_user = User::where('id', $contact->has_login)->first();
			$client_user->sendEventDenyNotification($client_user);			
			return redirect('/dashboard/calendar/events')->with('status', 'Event '.$event->name.' denied and email sent to client.');
		} else {
			return redirect('/dashboard/calendar/events/')->withErrors(['Could not find event']);			
		}
	}
	
	private function fix_date($dts, $dte)
	{
		$d = Carbon\Carbon::parse($dts)->format('Y-m-d');
		$dt = Carbon\Carbon::parse($d. " " . $dte.":00", 'America/Chicago')->format('Y-m-d H:i:s');
		return $dt;
	}
}
