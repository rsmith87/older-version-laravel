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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
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
    $messages = [];
    $participant = Participant::where('user_id', $this->user['id'])->get();
    foreach($participant as $index=>$p){
      if($index < 5){
        $messages[] = Message::where('thread_id', $p->thread_id)->get();
      }
    }
   
    $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
    $cases = LawCase::where('u_id', $this->user['id'])->get();
    $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'user_id' => $this->user['id'], 'is_client' => 0])->get();      
    $tasks = TaskList::where("user_id", $this->user['id'])->get();
  
      return view('dashboard/dashboard', [
        'user_name' => $this->user['name'], 
        'firm_id' => $this->settings->firm_id,
        'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        'user' => $this->user,
        'messages' => $messages,
        'clients' => $clients,
        'tasks' => $tasks,
        'cases' => $cases,
        'contacts' => $contacts,
        'status_values' => $this->status_values,
      ]);
    }
  
  
    public function create_roles_and_permissions()
    {
      //print_r($this->user->getRoleNames());
      // Create a superadmin role for the admin users
      /*
      $role = Role::create(['guard_name' => 'admin', 'name' => 'superadmin']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'administrator']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'authenticated_user']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'client']);
      */
      /*
      $permission = Permission::create(['name' => 'view cases']);
      $permission = Permission::create(['name' => 'view clients']);
      $permission = Permission::create(['name' => 'view contacts']);
      $permission = Permission::create(['name' => 'view firm']);
      $permission = Permission::create(['name' => 'view invoices']);
      $permission = Permission::create(['name' => 'view calendar']);
      $permission = Permission::create(['name' => 'view messages']);
      $permission = Permission::create(['name' => 'view tasks']);
      $permission = Permission::create(['name' => 'view documents']);
      $permission = Permission::create(['name' => 'view reports']);
      $permission = Permission::create(['name' => 'view settings']);
      */
     /* $role = Role::findByName('administrator');
      $role->givePermissionTo('view cases');
      $role->givePermissionTo('view clients');   
      $role->givePermissionTo('view contacts');
      $role->givePermissionTo('view firm');
      $role->givePermissionTo('view invoices');
      $role->givePermissionTo('view calendar');
      $role->givePermissionTo('view messages');
      $role->givePermissionTo('view tasks');
      $role->givePermissionTo('view documents');
      $role->givePermissionTo('view reports');
      $role->givePermissionTo('view settings');
      //give master admin administrator role
     // User::find(2)->assignRole('administrator');*/
      return redirect('/dashboard')->with('status', 'Roles and permissions created!');
     
      
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
    print_r($data);
    exit;
    
    $timer = Timer::where('user_id', $this->user['id'])->update(['timer' => $data['timer']]);
    return "updated";
  }    
  

}  
