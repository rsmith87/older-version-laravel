<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon;
use App\Task;
use App\Subtask;
use App\LawCase;
use App\Contact;
use App\Settings;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskController extends Controller
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
            $this->user_id = $this->user['id'];
            $this->settings = Settings::where('user_id', $this->user_id)->first();
            return $next($request);
        });
    }
  
    public function index(Request $request)
    {
      
   
      $tasks = Task::where('user_id', \Auth::id())->with('subtasks')->get();
      $subtasks = Subtask::where('user_id', \Auth::id())->get();
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
      $contacts = Contact::where('firm_id', $this->settings->firm_id)->select('id', 'first_name', 'last_name')->get();
      return view('dashboard/tasks', [
        'tasks'=> $tasks, 
        'subtasks' => $subtasks,
        'user_name' => $this->user['name'], 
        'theme' => $this->settings->theme, 
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        'cases' => $cases,
        'contacts' => $contacts,
				'firm_id' => $this->settings->firm_id,
      ]
     );
      
    }
  
    public function add(Request $request)
    {
      $data = $request->all();
      $status = "updated";
      if(!isset($data['id'])){
        $data['id'] = \DB::table('tasks')->max('id') + 1; 
        $status = "added";
      }      
  
      Task::updateOrCreate(
      [
        'id' => $data['id'],
      ],
      [
        'user_id' => $this->user->id,
        'task_name' => $data['task_name'],
        'task_description' => $data['task_description'],
        'f_id' => $this->settings->firm_id,
        'c_id' => $data['c_id'],
        'contact_client_id' => $data['contact_id'],
        'due' => $this->fix_date($data['due_date'], $data['due_time']),
        'assigned' => 0,
        
      ]);
             
      return redirect('/dashboard/tasks')->with('status', 'Task ' . $data['task_name'] . ' ' . $status ."!");
    }
  
    public function add_subtask(Request $request)
    {
      $data = $request->all();
      $status = 'updated';
      if(!isset($data['id'])){
        $data['id'] = null;
        $status = 'added';
      }
      
      Subtask::updateOrCreate([
        'id' => $data['id'],
      ],
      [
        't_id' => $data['t_id'],
        'subtask_name' => $data['task_name'],
        'f_id' => $this->settings->firm_id,
        'c_id' => $data['c_id'],
        'contact_client_id' => $data['c_c_id'],
        'due' => $this->fix_date($data['due_date'], $data['due_time']),
        'user_id' => \Auth::id(),
      ]);
      
      return redirect('/dashboard/tasks/')->with('status', 'Subtask '.$status ."!");
    }
  
    private function fix_date($dts, $dte)
    {
      $d = Carbon\Carbon::parse($dts)->format('Y-m-d');
      $dt = Carbon\Carbon::parse($d. " " . $dte.":00", 'America/Chicago')->format('Y-m-d H:i:s');
      return $dt;
    }

}
