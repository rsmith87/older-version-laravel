<?php

namespace App\Http\Controllers\Dashboard;

use App\Settings;
use App\TaskList;
use App\LawCase;
use App\Timer;
use App\Contact;
use App\Event;
use App\User;
use App\Invoice;
use App\FirmStripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Firm;
use MercurySeries\Flashy\Flashy;

class DashboardController extends Controller {

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware(function ($request, $next) {
      $this->user = \Auth::user();
      if (!$this->user) {
        return redirect('/login');
      }
      $this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->status_values = ['choose..', 'potential', 'active', 'closed', 'rejected'];
      if (!isset($this->settings->firm_id) || $this->settings->firm_id === 0) {
        return redirect('/dashboard/firm')->with('status', 'First, let\'s setup your firm.  Input the fields below to start.');
      }
      $this->s3 = \Storage::disk('s3');
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      //$this->threads = Thread::forUser(\Auth::id())->get();
      $this->case_types = ['choose..', 'personal_injury', 'estate_and_probate'];
      $this->event_types = ['court', 'client meeting', 'blocker', 'lunch', 'meeting', 'research', 'booked'];

      return $next($request);
    });
    //$this->user = \Auth::user();
  }


	public function add_payment(Request $request)
	{
		return view('vendor/adminlte/payment');
	}
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    $task_count = 0;
    $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
    $cases = LawCase::where('u_id', $this->user['id'])->get();
    $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'user_id' => $this->user['id'], 'is_client' => 0])->get();

    $tasklists = TaskList::where(['user_id' => $this->user['id'], 'show_dashboard' => 1])->with('task')->get();
    foreach ($tasklists as $tl) {
      foreach ($tl->Task as $dt) {
        $task_count += count($dt);
      }
    }
    $fs = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
    $events = Event::where('u_id', $this->user['id'])->get();
    $invoices = Invoice::where('user_id', $this->user['id'])->get();
    $firm = \App\Firm::where('id', $this->settings->firm_id)->first();
    $firm_message = \App\FirmMessage::where('firm_id', $this->settings->firm_id)->orderBy('created_at', 'desc')->first();
    return view('dashboard/dashboard', [
        'user' => $this->user,
        'firm_id' => $this->settings->firm_id,
        'firm' => $firm,
        'case_types' => $this->case_types,
        'settings' => $this->settings,
        'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        //'messages' => $messages,
        'clients' => $clients,
        'tasklists' => $tasklists,
        'cases' => $cases,
        'contacts' => $contacts,
        'events' => $events,
        'status_values' => $this->status_values,
        'invoices' => $invoices,
        'task_count' => $task_count,
	    'firm_message' => $firm_message,
        'types' => $this->event_types,
        'event_types' => $this->event_types,
    ]);
  }

  public function timer() {
    $timer = Timer::updateOrCreate([
                'user_id' => $this->user['id'],
                    ], [
                'start' => Carbon::now(),
    ]);
  }

  public function timer_stop(Request $request) {
    $timer = Timer::where('user_id', $this->user['id'])->update(['stop' => Carbon::now()]);
    return 'timer stopped';
  }

  public function timer_amount() {
    $timer = Timer::where('user_id', $this->user['id'])->first();
    return $timer;
  }

  public function timer_pause(Request $request) {
    $data = $request->all();

    $timer = Timer::where('user_id', $this->user['id'])->update(['timer' => $data['timer']]);
    return "updated";
  }

  public function timer_page(Request $request) {
    $data = $request->all();


    $timer = Timer::where('user_id', $this->user['id'])->update(['timer' => $data['timer']]);
    return "updated";
  }

  public function profile() {
    $settings = Settings::where('user_id', \Auth::id())->first();
    return view('dashboard/profile', [
        'user' => $this->user,
        'settings' => $settings,
        'firm_id' => $this->settings->firm_id,
        'theme' => $this->settings->theme,
        'profile_image' => $this->settings->profile_image,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        'types' => $this->case_types,
        'event_types' => $this->event_types,
    ]);
  }

  public function profile_update(Request $request) {
    $data = $request->all();
    $profile_image = $request->file('file_upload');
    $filePath = "";

    if ($request->file('file_upload') && $profile_image != "") {
      $imageFileName = time() . '.' . $request->file('file_upload')->getClientOriginalExtension();
      $filePath = '/avatars/' . $this->settings->firm_id . '/users/' . $imageFileName;
      $fileMimeType = $request->file('file_upload')->getMimeType();
	    \Storage::disk('local')->put($filePath, file_get_contents($request->file('file_upload')));
	    \Storage::disk('local')->url($filePath);
    }


    $settings = Settings::where('user_id', \Auth::id())->update([
        'location' => $data['location'],
        'education' => $data['education'],
        'title' => $data['title'],
        'profile_image' => $filePath != "" ? $filePath : $this->settings->profile_image,
    ]);

    User::where('id', \Auth::id())->update([
        'name' => $data['name'],
    ]);
    Flashy::message('Welcome Aboard!', 'http://your-awesome-link.com');
    return redirect()->back()->with('status', 'Profile updated successfully!');
  }

  public function lock() {
    // only if user is logged in
    if ($this->user) {
      \Session::put('locked', true);
      return view('dashboard.lock', [
          'user' => $this->user,
      ]);
    } else {
      return redirect('/login');
    }
  }

  public function unlock(Request $request) {
    $data = $request->all();
    // if user in not logged in 
    if (!\Auth::check()) {
      return redirect('/dashboard/lock')->withErrors(['You must be logged in to unlock your account.']);
    }
    $password = $data['password'];

    if (\Hash::check($password, \Auth::user()->password)) {
      \Session::forget('locked');
      return redirect('/dashboard');
    } else {
      return redirect()->back()->withErrors(['Your password was incorrect!']);
    }
  }

  public function api_passport(Request $request)
  {
  	return view('dashboard.admin', [

		  'user' => $this->user,
	  ]);
  }

  public function cancel_account(Request $request)
  {
      $user = \Auth::user();

      $cancel = $user->subscription('main')->cancel();

      \Auth::logout();
      return redirect('/login')->with('status', "Your subscription has been cancelled.  Please renew your membership to access your account.");
  }
}
