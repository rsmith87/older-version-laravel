<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\LawCase;
use App\Contact;
use App\Settings;
use App\View;
use App\Order;
use App\Http\Controllers\Controller;
use Carbon;

class CaseController extends Controller
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
    if(!isset($this->settings->firm_id) || $this->settings->firm_id === 0){
      return redirect('/dashboard/firm/')->with('status', 'You must provide your firm information before proceeding.');
    }
    
    $all_case_data = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('contacts')->with('documents')->with('tasks')->get();
    $columns = [];
    $views = View::where(['u_id' => $this->user_id, 'view_type' => 'case'])->get();
    $view_data_columns = [];
    if(count($views) > 0 && $views[0]->view_data != ""){
      foreach($views as $view_data){
        $data = $view_data->view_data;
      }
      $columns = json_decode($data, true);
    }
    else{
      $columns = ["id", "number", "name", "description", "court_name", "opposing_councel", "statute_of_limitations", "billing_rate"];
    }
    
    $cases = LawCase::where(["firm_id" => $this->settings->firm_id, 'u_id' => \Auth::id()])->select($columns)->with('contacts')->with('documents')->get();
   
    $status_values = ["select one", "active", "inactive"];
    
    return view('dashboard/cases', 
    [
      'user_name' => $this->user['name'],
      'cases' => $cases,
      'columns' => $columns,
      'firm_id' => $this->settings->firm_id,
      'theme' => $this->settings->theme,
      'status_values' => $status_values,
      'all_case_data' => $all_case_data,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,
    ]);
  }
  
  
  public function add(Request $request)
  {
    $data = $request->all();
         
    if(array_key_exists('id', $data)){
      $id = $data['id'];
    }
    else {
      $id = \DB::table('case')->max('id') + 1;
    }
    
    if(isset($data['statute_of_limitations'])){
      $date = new \DateTime();
      $date = $date->getTimestamp();
    }
    else {
      $date = "";
    }
    
    LawCase::updateOrCreate(
    [
      'id' => $id, 
    ],
    [
      'status' => $data['status'], 
      'number' => $data['case_number'],
      'name' => $data['name'],
      'description' => $data['description'],
      'court_name' => $data['court_name'],
      'opposing_councel' => $data['opposing_councel'],
      'claim_reference_number' => $data['claim_reference_number'],
      'location' => $data['location'],
      'open_date' => $this->fix_date($data['open_date']),
      'close_date' => $this->fix_date($data['close_date']),
      'statute_of_limitations' => $date,
      'is_billable' =>  isset($data['rate']) ? "1" : "0",
      'billing_type' => isset($data['rate_type']) ? 'hourly' : 'fixed',
      'billing_rate' => $data['billing_rate'],
      'hours' => $data['hours'],
      'firm_id' => $this->settings->firm_id,
      'u_id' => \Auth::id(),
    ]);
      
    return redirect('/dashboard/cases')->with('status', 'Case '.$data['name'].' has been updated!');
    }
  
  
  
  public function case($id, Request $request)
  {
    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id])->with('contacts')->with('documents')->first();
    if(!$requested_case){
      return redirect('/dashboard/cases')->withError('You don\'t have access to this case.');
    }
    if($requested_case->billing_type === 'fixed'){
      $invoice_amount = $requested_case->billing_rate;
    } else {
      $invoice_amount = $requested_case->billing_rate * $requested_case->hours;
    }
    $clients = Contact::where(['case_id' => $id, 'is_client' => 1])->get();
    $status_values = ['choose..', 'active', 'inactive'];
    return view('dashboard.case', [
      'user_name' => $this->user['name'],
      'case' => $requested_case,
      'firm_id' => $this->settings->firm_id,
      'theme' => $this->settings->theme,
      'clients' => $clients,
      'status_values' => $status_values,
      'invoice_amount' => $invoice_amount,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,          
    ]);
    
  }
  
  
  public function add_hours(Request $request)
  {
    $data = $request->all();
    $case = LawCase::where('id', $data['case_id'])->select(['hours', 'billing_rate'])->first();

    $new_hours = $case->hours + $data['hours_worked'];
    LawCase::where('id' , $data['case_id'])->update(['hours' => $new_hours]);
    if(!empty(Order::where('case_id', $data['case_id'])->first())){
     Order::where('case_id', $data['case_id'])->update(['total_amount' => $case->hours * $case->billing_rate]);
    }
    return redirect('/dashboard/cases/case/'.$data['case_id'])->with('status', 'Hours updated');
  }
  
    
  private function fix_date($dts)
  {
    $d = Carbon\Carbon::parse($dts)->format('Y-m-d');
    $dt = Carbon\Carbon::parse($dts . " " . '00:00:00', 'America/Chicago')->format('Y-m-d H:i:s');
    return $dt;
  }
}
