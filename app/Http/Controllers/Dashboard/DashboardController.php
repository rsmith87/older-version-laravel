<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\User;
use App\Settings;
use App\Task;
use App\TaskList;
use App\LawCase;
use Storage;
use App\Timer;
use App\Contact;
//use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class DashboardController extends Controller
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
          $this->status_values = ['choose..', 'potential', 'active', 'closed', 'rejected'];         
          if(!isset($this->settings->firm_id)){
            return redirect('/dashboard/firm')->with('status', 'First, let\'s setup your firm.  Input the fields below to start.');
          }
          $this->s3 = \Storage::disk('s3');

          return $next($request);
        });
        //$this->user = \Auth::user();
    }
  
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    //$threads = []; 
      
      
    //$messages = [];
   // $participant = Participant::where('user_id', $this->user['id'])->get();
    //foreach($participant as $index=>$p){
   //   if($index < 5){
    //    $messages[] = Message::where('thread_id', $p->thread_id)->get();
   //   }
   // }
      //print_r($messages);
   
    $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
    $cases = LawCase::where('u_id', $this->user['id'])->get();
    $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'user_id' => $this->user['id'], 'is_client' => 0])->get();   
    
		$tasks = TaskList::where('user_id', $this->user['id'])->with('dashboard_task')->get();
      
      return view('dashboard/dashboard', [
        'user' => $this->user, 
        'firm_id' => $this->settings->firm_id,
        'settings' => $this->settings,
        'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        //'messages' => $messages,
        'clients' => $clients,
        'tasks' => $tasks,
        'cases' => $cases,
        'contacts' => $contacts,
        'status_values' => $this->status_values,
      ]);
    }
 
  
  public function timer()
  {
    $timer = Timer::updateOrCreate([
      'user_id' => $this->user['id'],
    ], [
      'start' => Carbon::now(),
    ]);
  }
  
  public function timer_stop(Request $request)
  {
    $timer = Timer::where('user_id', $this->user['id'])->update(['stop' => Carbon::now()]);
    return 'timer stopped';
  }
  
  public function timer_amount()
  {
    $timer = Timer::where('user_id', $this->user['id'])->first();
    return $timer;
  }
  
  public function timer_pause(Request $request)
  {
    $data = $request->all();
    
    $timer = Timer::where('user_id', $this->user['id'])->update(['timer' => $data['timer']]);
    return "updated";
  }  
  
  public function timer_page(Request $request)
  {
    $data = $request->all();

    
    $timer = Timer::where('user_id', $this->user['id'])->update(['timer' => $data['timer']]);
    return "updated";
  }
  
  public function profile()
  {
    $settings = Settings::where('user_id', \Auth::id())->first();
    return view('dashboard/profile', [
        'user' => $this->user, 
        'settings' => $settings,
        'firm_id' => $this->settings->firm_id,
        'theme' => $this->settings->theme,
        'profile_image' => $this->settings->profile_image,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
      
    ]);
  }
  
  public function profile_update(Request $request)
  {
    $data = $request->all();
    $profile_image = $request->file('file_upload');
    $filePath = "";

    if($request->file('file_upload')){
      $imageFileName = time() . '.' . $request->file('file_upload')->getClientOriginalExtension();
      $filePath = '/f/'.$this->settings->firm_id.'/u/'.$this->user['id'].'/' .$imageFileName;
      $fileMimeType = $request->file('file_upload')->getMimeType();
      $this->s3->put($filePath, file_get_contents($request->file('file_upload')));
      $this->s3->url($filePath);  
    }
     $settings = Settings::where('user_id', \Auth::id())->update([
      'location' => $data['location'],
      'experience' => $data['experience'],
      'focus' => $data['skills'],
      'education' => $data['education'],
      'title' => $data['title'],
      'profile_image' => $filePath,
    ]);
    return redirect()->back()->with('status', 'Profile updated successfully!');
  }
  

}  
