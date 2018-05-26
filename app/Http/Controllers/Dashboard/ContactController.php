<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contact;
use App\User;
use App\View;
use App\LawCase;
use App\Note;
use App\Document;
use App\Settings;
use App\TaskList;
use App\CommLog;
use App\FirmStripe;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;

class ContactController extends Controller
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
			if(!$this->user->hasPermissionTo('view contacts')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}					
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			return $next($request);
		});
	}

	public function index(Request $request)
	{
		$columns = [];
		$views = View::where(['u_id' => $this->user['id'], 'view_type' => 'contact'])->get();

		$view_data_columns = [];
		
		if(count($views) > 0 && $views[0]->view_data != ""){
			foreach($views as $view_data){
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
			//array_unshift($columns, 'contlient_uuid');
		}
		else{
			$columns = ["contlient_uuid", "first_name", "last_name", "phone", "email"];
		}
		
		$array_cases = [];
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
		
		foreach($cases as $case){
			$array_cases[$case->id] =	$case->name;
		}


		$contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->select($columns)->with('documents')->with('tasks')->get();
		$other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->get();
   
		
		return view('dashboard/contacts', [
      'user' => $this->user,
			'columns' => $columns, 
			'views' => $views, 
			'contacts' => $contacts, 
			'other_data' => $other_data, 
			'user_name' => $this->user['name'], 
			'cases' => $cases, 
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
      'array_cases' => $array_cases,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,
		]);
	}

	public function contact(Request $request, $id)
	{
		
		
		$requested_contact = Contact::where(['firm_id' =>  $this->settings->firm_id, 'contlient_uuid' => $id, 'user_id' => $this->user['id']])->with('documents')->first();
		
		if(!$requested_contact){
			return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
		}
    $cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->get();
    $logs = CommLog::where(['type' => 'contact_client', 'type_id' => $id])->get();
    $task_lists = TaskList::where('contact_client_id', $id)->with('task')->get();
		$notes = Note::where('contlient_uuid', $id)->get();
		return view('dashboard.contact', [
			'user' => $this->user,
      'cases' => $cases,
			'contact' => $requested_contact,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'is_client' => 0,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size, 
      'notes' => $notes,
      'task_lists' => $task_lists,
      'logs' => $logs,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,        
		]);
	}

	public function client(Request $request, $id)
	{
		$requested_contact = Contact::where(['firm_id' =>  $this->settings->firm_id, 'contlient_uuid' => $id, 'is_client' => '1', 'user_id' => $this->user['id']])->with('documentsclients')->with('tasks')->first();
    
		if(!$requested_contact){
			return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
		}
    
    $cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->get();
    $notes = Note::where('contlient_uuid', $id)->get();
    $task_lists = TaskList::where('contact_client_id', $id)->with('task')->get();
    $logs = CommLog::where(['type' => 'contact_client', 'type_id' => $id])->get();

		return view('dashboard.contact', [
			'user' => $this->user,
			'contact' => $requested_contact,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'is_client' => 1,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,         
      'notes' => $notes,
      'task_lists' => $task_lists,
      'logs' => $logs,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,        
		]);
	}	

	public function clients(Request $request)
	{
		$columns = [];
		$views = View::where(['u_id' => $this->user->id, 'view_type' => 'client'])->get();

		$view_data_columns = [];
		
		if(count($views) > 0 && $views[0]->view_data != ""){
			foreach($views as $view_data){
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
		}
		else{
			$columns = ["id", "first_name", "last_name", "phone", "email"];
		}
		
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
		$contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1', 'user_id' => $this->user['id']])->select($columns)->with('documentsclients')->with('tasks')->get();
		$other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1'])->get();
		
		return view('dashboard/clients', [
      'user' => $this->user,
			'columns' => $columns, 
			'views' => $views, 
			'contacts' => $contacts, 
			'other_data' => $other_data, 
			'user_name' => $this->user['name'], 
			'cases' => $cases, 
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,  
			'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,        
		]);
	}

	public function add(Request $request)
	{
	$data = $request->all();

	$user = $this->user;
	$firm = $this->settings->firm_id;
		
	if(!isset($data['id'])){
		$data['id'] = \DB::table('contact')->max('id') + 1; 
		$updated = 'added';
	} else {
		$updated = 'updated';
	}
		
	if(!isset($data['case_id'])){
		$data['case_id'] = '';
	}
  if(!isset($data['relationship'])){
    $data['relationship'] = "";
  }
    $contlient_uuid = Uuid::generate()->string;

	if(empty($data['is_client']) or !isset($data['is_client']) || $data['is_client'] === '0'){
		$redirect = 'dashboard/contacts';
		$type = 'Contact';
		
		Contact::updateOrCreate(
		[
			'id' => $data['id'],
		],
		[
      'contlient_uuid' => $contlient_uuid,                
			'first_name' => $data['first_name'], 
			'last_name' => $data['last_name'],
      'relationship' => $data['relationship'],
			'company' => $data['company'],
			'company_title' => $data['company_title'],
			'email' => $data['email'],
			'phone' => $data['phone'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'city' => $data['city'],
			'state' => $data['state'],
			'zip' => $data['zip'],
			'user_id' => $this->user['id'],
			'firm_id' => $this->settings->firm_id,
			'case_id' => $data['case_id'],
			'is_client' => '0',
		]);        

	}
	elseif($data['is_client'] === '1') {
		$redirect = 'dashboard/clients';
		$type = 'Client';
    
    $case = LawCase::where('id', $data['case_id'])->get();
		
    if(count($case) === 0){
      
		Contact::updateOrCreate(
		[
			'id' => $data['id'],
		],
		[
      'contlient_uuid' => $contlient_uuid,        
			'first_name' => $data['first_name'], 
			'last_name' => $data['last_name'],
			'company' => $data['company'],
			'company_title' => $data['company_title'],
			'email' => $data['email'],
			'phone' => $data['phone'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'city' => $data['city'],
			'state' => $data['state'],
			'zip' => $data['zip'],
			'user_id' => $this->user['id'],
			'firm_id' => $this->settings->firm_id,
			'case_id' => $data['case_id'],
			'is_client' => '1',
		]);   
      
    } else {
      return redirect()->back()->withErrors(['A case can only have one client.']);
    }
  }
  
    
		
	  $status = $type . " " . $data['first_name'] . " " . $data['last_name'] . " " .$updated."!";           

		return redirect($redirect)->with('status', $status);

	}
  
    public function note_add(Request $request)
    {
    $data = $request->all();
    
    $note = Note::create([
      'case_id' => 0,
       'contlient_uuid' => $data['contlient_uuid'],
       'note' => $data['note'],
       'user_id' => $this->user['id'],
      'firm_id' => $this->settings->firm_id,
    ]);
    
    return back();
    
  }
  
  public function note_edit(Request $request)
  {
    $data = $request->all();
    
    $note = Note::where('id', $data['id'])->update(['note' => $data['note']]);
    return redirect()->back()->with('status', 'Note edited successfully');
  }
  
  public function note_delete(Request $request)
  {
    $data = $request->all();
    
    $note = Note::where('id', $data['id'])->delete();
    return redirect()->back()->with('status', 'Note deleted successfully');
  }
  
  public function log_communication(Request $request)
  {
    $data = $request->all();
    
    $type_id = $data['contact_client_id'];
    
    $log = CommLog::create([
      'type' => 'contact_client',
      'type_id' => $type_id,
      'comm_type' => $data['communication_type'],
      'log' => $data['communication'],
    ]);
    
    return redirect()->back()->with('status', 'Communication logged!');
    
  }
  
}
