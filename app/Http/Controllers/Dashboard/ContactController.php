<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contact;
use App\User;
use App\View;
use App\LawCase;
use App\Document;
use App\Settings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Controller;

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
            $this->user_id = $this->user['id'];
            $this->settings = Settings::where('user_id', $this->user_id)->first();
            return $next($request);
        });
    }
  
    public function index(Request $request)
    {

     
      
      $columns = [];
      $views = View::where(['u_id' => $this->user_id, 'view_type' => 'contact'])->get();

        $view_data_columns = [];
        if(count($views) > 0 && $views[0]->view_data != ""){
          foreach($views as $view_data){
            $data = $view_data->view_data;
          }
          $columns = json_decode($data, true);
          array_unshift($columns, 'id');

        }
        else{
          $columns = ["id", "first_name", "last_name", "phone", "email"];
        }
			$array_cases = [];
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
			foreach($cases as $case){
				$array_cases[$case->id] =	$case->name;
			}
      $contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->select($columns)->with('documents')->with('tasks')->get();
      $other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->get();
      //$documents = Document::where(['firm_id' => $this->settings->firm_id])->get();
      return view('dashboard/contacts', [
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
      ]);
    }
  
		public function contact(Request $request, $id)
		{

			$array_cases = [];
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
			foreach($cases as $case){
				$array_cases[$case->id] =	$case->name;
			}
			//print_r($array_cases);
			$requested_contact = Contact::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id, 'user_id' => \Auth::id()])->with('documents')->with('tasks')->first();
			if(!$requested_contact){
				return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
			}
			
			return view('dashboard.contact', [
				'user_name' => $this->user['name'],
				'contact' => $requested_contact,
				'firm_id' => $this->settings->firm_id,
				'theme' => $this->settings->theme,
				'cases' => $cases,
				'array_cases' => $array_cases,
				'is_client' => 0,
				'table_color' => $this->settings->table_color,
				'table_size' => $this->settings->table_size,          
			]);
		}
	
	public function client(Request $request, $id)
		{
			$array_cases = [];
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
			foreach($cases as $case){
				$array_cases[$case->id] =	$case->name;
			}
			$requested_contact = Contact::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id, 'is_client' => '1', 'user_id' => \Auth::id()])->with('documentsclients')->with('tasks')->first();
			if(!$requested_contact){
				return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
			}
			return view('dashboard.contact', [
				'user_name' => $this->user['name'],
				'contact' => $requested_contact,
				'firm_id' => $this->settings->firm_id,
				'theme' => $this->settings->theme,
				'cases' => $cases,
				'array_cases' => $array_cases,
				'is_client' => 1,
				'table_color' => $this->settings->table_color,
				'table_size' => $this->settings->table_size,          
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
      $contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1', 'user_id' => \Auth::id()])->select($columns)->with('documentsclients')->with('tasks')->get();
			$other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1'])->get();
      return view('dashboard/clients', [
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
    
      if(empty($data['is_client']) or !isset($data['is_client']) || $data['is_client'] === '0'){
        $redirect = 'dashboard/contacts';
        $type = 'Contact';
        Contact::updateOrCreate(
        [
          'id' => $data['id'],
        ],
        [
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
					'user_id' => \Auth::id(),
          'firm_id' => $this->settings->firm_id,
          'case_id' => $data['case_id'],
          'is_client' => '0',
        ]);        

      }
      elseif($data['is_client'] === '1')
      {
        $redirect = 'dashboard/clients';
        $type = 'Client';
      Contact::updateOrCreate(
      [
        'id' => $data['id'],
      ],
      [
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
				'user_id' => \Auth::id(),
        'firm_id' => $this->settings->firm_id,
        'case_id' => $data['case_id'],
        'is_client' => '1',
      ]);     
      

    
      
    }
              $status = $type . " " . $data['first_name'] . " " . $data['last_name'] . " " .$updated."!";           

            return redirect($redirect)->with('status', $status);

  }
}
