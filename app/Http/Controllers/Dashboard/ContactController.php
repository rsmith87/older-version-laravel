<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Role;
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
        $this->middleware('auth');
        $this->user = \Auth::user();
        $this->user_id = \Auth::id();
        $this->settings = Settings::where('user_id', $this->user_id)->first();
    }
  
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['auth_user', 'administrator']);
      $not_allowed = $request->user()->hasRole('administrator');  

     
      
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
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
      $contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->select($columns)->with('documents')->get();
      $other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '0'])->get();
      //$documents = Document::where(['firm_id' => $this->settings->firm_id])->get();
      return view('dashboard/contacts', [
        'columns' => $columns, 
        'views' => $views, 
        'contacts' => $contacts, 
        'other_data' => $other_data, 
        'user_name' => $this->user['name'], 
        'cases' => $cases, 
        'theme' => $this->settings->theme,
        'role' => $not_allowed,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
      ]);
    }
  
    public function clients(Request $request)
    {
      $request->user()->authorizeRoles(['auth_user', 'administrator']);

      $not_allowed = $request->user()->hasRole('administrator');  
      
      $columns = [];
      $views = View::where(['u_id' => $this->user->id, 'view_type' => 'client'])->get();

        $view_data_columns = [];
        if(count($views) > 0 && $views[0]->view_data != ""){
          foreach($views as $view_data){
            $data = $view_data->view_data;
          }
          $columns = json_decode($data, true);
          //array_unshift($columns);

        }
        else{
          $columns = ["id", "first_name", "last_name", "phone", "email"];
        }
      $cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
      $contacts = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1'])->select($columns)->get();
      $other_data = Contact::where([ "firm_id"=> $this->settings->firm_id, 'is_client' => '1'])->with('documents_client')->get();
      return view('dashboard/clients', [
        'columns' => $columns, 
        'views' => $views, 
        'contacts' => $contacts, 
        'other_data' => $other_data, 
        'user_name' => $this->user['name'], 
        'cases' => $cases, 
        'theme' => $this->settings->theme,
        'role' => $not_allowed,
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
          'address' => $data['address'],
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
        'address' => $data['address'],
        'firm_id' => $this->settings->firm_id,
        'case_id' => $data['case_id'],
        'is_client' => '1',
      ]);     
      

    
      
    }
              $status = $type . " " . $data['first_name'] . " " . $data['last_name'] . " " .$updated."!";           

            return redirect($redirect)->with('status', $status);

  }
}
