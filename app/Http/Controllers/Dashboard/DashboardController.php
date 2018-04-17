<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\User;
use App\Settings;
use App\Task;
use App\Document;
use App\LawCase;
use App\WysiwygDocument;
use App\View;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Contact;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

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
      if($this->user->created_at === $this->user->updated_at){
        //how to tell if new user, but doesn't discriminate between users for roles
      }    
      
      if(count($this->settings) < 1){
        Settings::create([
          'user_id' => \Auth::id(),
          'theme' => 'flatly',
          'table_color' => 'light',
          'table_size' => 'lg',
        ]);
      }
      if(count(View::where('u_id', \Auth::id())->first()) < 1){
        View::create([
          'view_type' => 'contact',
          'view_data' => json_encode(array('id', 'first_name', 'last_name', 'phone'), true),
          'u_id' => \Auth::id(),
        ]);  

        View::create([
          'view_type' => 'case',
          'view_data' => json_encode(array('id', 'name', 'court_name'), true),
          'u_id' => \Auth::id(),
        ]); 
        View::create([
          'view_type' => 'client',
          'view_data' => json_encode(array('id', 'first_name', 'last_name', 'phone'), true),
          'u_id' => \Auth::id(),
        ]);        
      }
      
    //$threads = []; 
    $participant = Participant::where('user_id', $this->user['id'])->get();
    foreach($participant as $p){
      $messages[] = Message::where('thread_id', $p->thread_id)->get();
    }
   
    $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
    $cases = LawCase::where('u_id', $this->user['id'])->get();
    $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'user_id' => $this->user['id'], 'is_client' => 0])->get();      
      
    $tasks = Task::where("user_id", $this->user['id'])->get();
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
      ]);
    }
  
  
    public function create_roles_and_permissions()
    {
      //print_r($this->user->getRoleNames());
      //give master admin administrator role
     // User::find(9)->assignRole('client');
      return redirect('/dashboard')->with('status', 'Roles and permissions created!');
     
      
    }
  

}  
