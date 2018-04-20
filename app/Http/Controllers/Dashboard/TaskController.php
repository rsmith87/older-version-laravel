<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon;
use App\Task;
use App\Subtask;
use App\LawCase;
use App\Contact;
use App\Category;
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
      if(!$this->user){
				return redirect('/login');
			}			
			if(!$this->user->hasPermissionTo('view tasks')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}		
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			return $next($request);
		});
	}

	public function index(Request $request)
	{
		$tasks = Task::where('user_id', $this->user['id'])->with('subtasks')->with('categories')->get();
		$subtasks = Subtask::where('user_id', $this->user['id'])->get();
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
		]);
	}
	public function view($id)
	{
		$values="";
		$count = 0;
    $exis_cat = [];
		$task = Task::where(['id' => $id])->with('subtasks')->with('categories')->first();
		if(!empty($task)){
			 $cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
			 $contacts = Contact::where('firm_id', $this->settings->firm_id)->select('id', 'first_name', 'last_name')->get();
			 foreach($task->Categories as $cat){
				 $exis_cat[] = \DB::table('task_categories')->where('id', $cat->category_id)->first();
			 }
			foreach($exis_cat as $c){
				$values .= $c->name.',';
				$count++;
			}
			print_r($count);
			return view('dashboard/task', [
				'task' => $task,
				'tags' => $exis_cat,
				'values' => $values,
				'count' => $count,
				'user_name' => $this->user['name'], 
				'theme' => $this->settings->theme, 
				'table_color' => $this->settings->table_color,
				'table_size' => $this->settings->table_size,
				'cases' => $cases,
				'contacts' => $contacts,
				'firm_id' => $this->settings->firm_id,
			 ]);
		}
		else{
			return redirect('/dashboard/tasks')->withErrors(['Invalid task or you don\'t have permission to access it.']);
		}
	}
	
	public function add(Request $request)
	{
		$data = $request->all();
		$status = "updated";
		
		if(!isset($data['id'])){
			$data['id'] = \DB::table('tasks')->max('id') + 1; 
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
	print_r($data['id']);
		return redirect('/dashboard/tasks/')->with('status', 'Task ' . $data['task_name'] . ' ' . $status ."!");
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
			'user_id' => $this->user['id'],
		]);

		return redirect('/dashboard/tasks/')->with('status', 'Subtask '.$status ."!");
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
