<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\LawCase;
use App\Contact;
use App\Settings;
use App\View;
use App\Order;
use App\Document;
use App\Invoice;
use App\InvoiceLine;
use App\Http\Controllers\Controller;
use Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
      if(!$this->user){
				return redirect('/login');
			}      
      if(!$this->user->hasPermissionTo('view cases')){
        return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
      }
      $this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->get();
      $this->contacts = Contact::where(['user_id' => $this->user['id'], 'is_client' => 0])->get();
      $this->clients = Contact::where(['user_id' => $this->user['id'], 'is_client' => 1])->get();
      $this->status_values = ['choose..', 'potential', 'active', 'closed', 'rejected'];
      
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
    $views = View::where(['u_id' => $this->user['id'], 'view_type' => 'case'])->get();
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
    
    return view('dashboard/cases', 
    [
      'user_name' => $this->user['name'],
      'cases' => $cases,
      'columns' => $columns,
      'firm_id' => $this->settings->firm_id,
      'theme' => $this->settings->theme,
      'status_values' => $this->status_values,
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
      'billing_type' => isset($data['rate_type']) ? $data['rate_type'] : 'fixed',
      'billing_rate' => $data['billing_rate'],
      'hours' => $data['hours'],
      'firm_id' => $this->settings->firm_id,
      'u_id' => \Auth::id(),
    ]);
    
      
    return redirect('/dashboard/cases/case/'.$id)->with('status', 'Case '.$data['name'].' has been updated!');
    }
  
  
  
  public function case($id, Request $request)
  {
   
    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id])->with('contacts')->with('documents')->first();
    if(count($requested_case) === 0){
      return redirect('/dashboard/cases')->withErrors(['You don\'t have access to this case.']);
    }
  
    if($requested_case->billing_type === 'fixed'){
      $invoice_amount = $requested_case->billing_rate;
    } else {
      $invoice_amount = $requested_case->billing_rate * $requested_case->hours;
    }
    $clients = Contact::where(['case_id' => $id, 'is_client' => 1])->get();
    $client_id = Contact::where(['case_id' => $id, 'is_client' => 1])->select('id')->first();
    $order = Order::where('case_id', $id)->first();
    $invoices = Invoice::where('invoicable_id', $id)->get();
    $documents = Document::where('case_id', $id)->get();
    foreach($invoices as $invoice){
      $line = InvoiceLine::where('invoice_id', $invoice->id)->select('amount')->first();
      $invoice_amount = $invoice_amount - $line->amount;
    }

    return view('dashboard.case', [
      'user_name' => $this->user['name'],
      'case' => $requested_case,
      'firm_id' => $this->settings->firm_id,
      'theme' => $this->settings->theme,
      'clients' => $clients,
      'order' => $order,
      'status_values' => $this->status_values,
      'invoice_amount' =>$invoice_amount,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,  
      'cases' => $this->cases,
      'contacts' => $this->contacts,
      'clients' => $this->clients,
      'documents' => $documents,
    ]);
    
  }
  
  
  public function add_hours(Request $request)
  {
    $data = $request->all();
    $case = LawCase::where('id', $data['case_id'])->select(['hours', 'billing_rate'])->first();

    //TODO: need to address how to update order amount on hours save as long as there is a billing_rate stored
    $new_hours = $case->hours + $data['hours_worked'];
    LawCase::where('id' , $data['case_id'])->update(['hours' => $new_hours]);
    //print_r(Order::where('case_id', $data['case_id'])->first());
    $order = Order::where('case_id', $data['case_id'])->first();
    if(count($order) > 0){
     Order::where('case_id', $data['case_id'])->update(['amount_remaining' => $order->amount_remaining + $case->hours * $case->billing_rate]);
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
