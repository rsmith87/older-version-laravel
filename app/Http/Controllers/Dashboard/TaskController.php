<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon;
use App\TaskList;
use App\Task;
use App\Subtask;
use App\LawCase;
use App\Contact;
use App\Category;
use App\Settings;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\FirmStripe;

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
      if(!$this->user){
				return redirect('/login');
			}			
			if(!$this->user->hasPermissionTo('view tasks')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}		
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			return $next($request);
		});
	}

  
	public function index(Request $request)
	{
    //reference TaskLIst to get the list by user id
    //then get the tasks by task list
    //return the task list name for the table rows
    //handle redirect in JS file like the others
    
		$tasks = TaskList::where('user_id', $this->user['id'])->with('tasks')->get();
		//$subtasks = Subtask::where('user_id', $this->user['id'])->get();
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
		$contacts = Contact::where('firm_id', $this->settings->firm_id)->select('id', 'first_name', 'last_name')->get();
		return view('dashboard/tasks', [
			'tasks'=> $tasks, 
			//'subtasks' => $subtasks,
			'user' => $this->user, 
			'theme' => $this->settings->theme, 
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'cases' => $cases,
			'contacts' => $contacts,
			'firm_id' => $this->settings->firm_id,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,        
		]);
	}
  
  
	public function view($id)
	{
		$values="";
		$count = 0;
    $exis_cat = [];
    $tasks = [];
    $cases = [];
		$task_list = TaskList::where(['task_list_uuid' => $id])->first();
    //print_r($task_list);
    if(count($task_list) > 0){
      $tasks = Task::where('task_list_uuid', $id)->with('subtasks')->with('categories')->get();
      $cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->select('id', 'name')->get();
			$contacts = Contact::where('firm_id', $this->settings->firm_id)->select('id', 'first_name', 'last_name')->get();    
    }
    
		if(count($tasks) > 0){
      foreach($tasks as $task){
			 foreach($task->Categories as $cat){
				 $exis_cat[] = \DB::table('task_categories')->where('id', $cat->category_id)->first();
			 }
			foreach($exis_cat as $c){
				$values .= $c->name.',';
				$count++;
			}
      }
		}

    	return view('dashboard/task', [
				'tasks' => !empty($tasks) ? $tasks : [],
        'task_list' => $task_list,
				'tags' => $exis_cat,
				'values' => $values,
				'count' => $count,
				'user' => $this->user, 
				'theme' => $this->settings->theme, 
				'table_color' => $this->settings->table_color,
				'table_size' => $this->settings->table_size,
				'cases' => !empty($cases) ? $cases : [],
				'contacts' => !empty($contacts) ? $contacts : "",
				'firm_id' => $this->settings->firm_id,
        'user_id' => $this->user['id'],
        'zero_datetime' => '0000-00-00 00:00:00',
        'settings' => $this->settings,
        'fs' => $this->firm_stripe,          
			 ]);
	}
  
  public function complete_subtask($id)
  {
    $subtask = Subtask::where('id', $id)->update(['complete' => Carbon\Carbon::now()]);
    return $subtask;
  }
  
 public function view_single_task($id, $t_id)
  {
		$values="";
		$count = 0;
    $exis_cat = [];
    $t = [];
    $cases = [];

    $t = Task::where('task_uuid', $t_id)->with('subtasks')->with('categories')->first();

    if(count($t) > 0){
      $cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->select('id', 'name')->get();
      $contacts = Contact::where('firm_id', $this->settings->firm_id)->select('id', 'first_name', 'last_name')->get();    
       foreach($t->Categories as $cat){
				 $exis_cat[] = \DB::table('task_categories')->where('id', $cat->category_id)->first();
       }
      foreach($exis_cat as $c){
        $values .= $c->name.',';
        $count++;
      }
    }
    else{
      return redirect('/dashboard/tasks')->withErrors(['Invalid task or you don\'t have permission to access it.']);
    }

    return view('dashboard/t', [
      'task' => $t,
      'tags' => $exis_cat,
      'values' => $values,
      'count' => $count,
      'tl_id' => $id,
      'user' => $this->user, 
      'theme' => $this->settings->theme, 
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,
      'cases' => !empty($cases) ? $cases : [],
      'contacts' => !empty($contacts) ? $contacts : "",
      'firm_id' => $this->settings->firm_id,
      'user_id' => $this->user['id'],
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,        
     ]);    
  }	
  
	public function add_tasklist(Request $request)
	{
		$data = $request->all();
		$status = "updated";
		
		if(!isset($data['id'])){
			$data['id'] = \DB::table('task_lists')->max('id') + 1; 
      $data['task_list_uuid'] = Uuid::generate()->string;
			$status = "added";
		}      
		$category = explode(',', $data['tags']);
		
		foreach($category as $c){
			//existing category
			$exis_cat = \DB::table('task_categories')->where('name', $c)->get();
			
			//creating a new category with name
			if(count($exis_cat) < 1)
			{	
				//INSERT INTO TASK CATEGORY AS NEW CATEGORY and put ID in the Category class
				$insert = \DB::table('task_categories')->insert([ ['name'=> $c] ]);
				$added = \DB::table('task_categories')->where('name', $c)->first();
				
				//NOW MAKE THE AFFILIATION ON Category for category ID and task ID
				Category::firstOrCreate( 
					[ 
						'category_id' => $added->id 
					], [ 
						'task_id' => $data['id']
					]);

				
			}
			//else THIS CATEGORY EXISTS
			else{
				$already = \DB::table('task_categories')->where('name', $c)->first();
				
				Category::firstOrCreate(
				[
					'category_id' => $already->id
				], [
					'task_id' => $data['id']
				]);
			}
		}
		
		TaskList::updateOrCreate(
		[
			'id' => $data['id'],
		],
		[
      'task_list_uuid' => Uuid::generate()->string,
			'user_id' => $this->user->id,
			'task_list_name' => $data['task_name'],
			'f_id' => $this->settings->firm_id,
			'c_id' => isset($data['case_id']) ? $data['case_id'] : "",
			'contact_client_id' => isset($data['contact_id']) ? $data['contact_id'] : "",
			'due' => $this->fix_date($data['due_date'], $data['due_time']),
			'assigned' => 0,
		]);
    
	//print_r($data['id']);
		return redirect('/dashboard/tasks/')->with('status', 'Task ' . $data['task_name'] . ' ' . $status ."!");
	}
  
  public function add_task(Request $request)
  {
		$data = $request->all();
		$status = "updated";
		
		if(!isset($data['id'])){
			$data['id'] = \DB::table('task_lists')->max('id') + 1; 
			$status = "added";
		}      


		$category = explode(',', $data['tags']);
	
		
		foreach($category as $c){
			//existing category
			$exis_cat = \DB::table('task_categories')->where('name', $c)->get();
			
			//creating a new category with name
			if(count($exis_cat) < 1)
			{	
				//INSERT INTO TASK CATEGORY AS NEW CATEGORY and put ID in the Category class
				$insert = \DB::table('task_categories')->insert([ ['name'=> $c] ]);
				$added = \DB::table('task_categories')->where('name', $c)->first();
				
				//NOW MAKE THE AFFILIATION ON Category for category ID and task ID
				Category::firstOrCreate( 
					[ 
						'category_id' => $added->id 
					], [ 
						'task_id' => $data['id']
					]);

				
			}
			//else THIS CATEGORY EXISTS
			else{
				$already = \DB::table('task_categories')->where('name', $c)->first();
				
				Category::firstOrCreate(
				[
					'category_id' => $already->id
				], [
					'task_id' => $data['id']
				]);
			}
		}
    if(empty($data['due_time'])){
      $data['due_time'] = "00:00";
    }
		
    $task = Task::create(
		[
      'task_list_uuid' => $data['tl_uuid'],
      'task_uuid' => Uuid::generate()->string,
      'task_name' => $data['task_name'],
      'task_description' =>  $data['task_description'],
			'contact_client_id' => isset($data['contact_id']) ? $data['contact_id'] : "",
			'due' => isset($data['due_date']) ? $this->fix_date($data['due_date'], $data['due_time']) : \Carbon\Carbon::parse("0000-00-00 00:00:00")->format('Y-m-d H:i:s'),
			'user_id' => \Auth::id(),
      'assigned' => 0,
		]);
	//print_r($data['id']);
		return redirect('/dashboard/tasks/task/'.$task->task_list_uuid)->with('status', 'Task ' . $data['task_name'] . ' ' . $status ."!");
  }

	public function add_subtask(Request $request)
	{
		$data = $request->all();
		$status = 'updated';

		if(!isset($data['id'])){
			$data['id'] = null;
			$status = 'added';
		}
    $data['subtask_description'] = '';

		Subtask::updateOrCreate([
			'id' => $data['id'],
		],
		[
			't_id' => $data['task_list_uuid'],
			'subtask_name' => $data['subtask_name'],
      'subtask_description' => $data['subtask_description'],
			'due' => $this->fix_date($data['due_date'], $data['due_time']),
			'user_id' => $this->user['id'],
		]);

		return redirect('/dashboard/tasks/task/'.$data['task_list_uuid'])->with('status', 'Subtask '.$status ."!");
	}
  
  public function delete_subtask(Request $request)
  {
    $data = $request->all();
    
    Subtask::where('id', $data['st_id'])->delete();
    return redirect()->back()->with('status', 'Subtask deleted');
  }
  
  public function delete(Request $request)
  {
    $data = $request->all();
    
    Task::where('task_uuid', $data['task_uuid'])->delete();
    return redirect()->back()->with('status', 'Task deleted');
  }
	
	public function delete_category($id)
	{
		$name = \DB::table('task_categories')->where('name', $id)->first();		
		Category::where('category_id', $name->id)->delete();
		return "deleted";
	}

	private function fix_date($dts, $dte)
	{
		$d = Carbon\Carbon::parse($dts)->format('Y-m-d');
		$dt = Carbon\Carbon::parse($d. " " . $dte.":00", 'America/Chicago')->format('Y-m-d H:i:s');
		return $dt;
	}

}
