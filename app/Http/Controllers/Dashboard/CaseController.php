<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\LawCase;
use App\Settings;
use App\View;
use App\Http\Controllers\Controller;

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
    
    $request->user()->authorizeRoles(['auth_user', 'administrator']);
    $not_allowed = $request->user()->hasRole('administrator');
    

    $firm_id = $this->settings->firm_id;

    $all_case_data = LawCase::where('firm_id', $firm_id)->with('contacts')->with('documents')->get();
    $columns = [];
    $views = View::where(['u_id' => $this->user_id, 'view_type' => 'case'])->get();
    //print_r($cases);
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
    
    $cases = LawCase::where("firm_id", $this->settings->firm_id)->select($columns)->with('contacts')->with('documents')->get();
   
    //$cases->statute_of_limitations =  \Carbon\Carbon::parse($cases->statute_of_limitations)->format('m/d/Y');
    $status_values = ["select one", "active", "inactive"];
    return view('dashboard/cases', 
    [
      'user_name' => $this->user['name'],
      'cases' => $cases,
      'columns' => $columns,
      'firm_id' => $firm_id,
      'theme' => $this->settings->theme,
      'status_values' => $status_values,
      'all_case_data' => $all_case_data,
      'role' => $not_allowed,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,
    ]);
  }
  
  
  public function add(Request $request)
  {
    $data = $request->all();
    $request->user()->authorizeRoles(['auth_user', 'administrator']);

         
    if(array_key_exists('id', $data)){
      $id = $data['id'];
    }
    else {
      $id = \DB::table('case')->max('id') + 1;
    }
    if(!isset($data['rate_type'])){
      $data['rate_type'] = 'rate_hourly';
    };
    
    if(isset($data['statute_of_limitations'])){
      $date = new \DateTime();
      $date = $date->getTimestamp();
    }
    else {
      $date = "";
    }
    
//print_r($data);
    //break;
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
      'claim_reference_number' => '2',
      'location' => $data['location'],
      'open_date' => $data['open_date'],
      'close_date' => $data['close_date'],
      'statute_of_limitations' => $date,
      'is_billable' =>  isset($data['rate']) ? "1" : "0",
      'billing_type' => isset($data['rate_type']) ? 'rate_hourly' : 'rate_fixed',
      'billing_rate' => $data['rate'],
      'firm_id' => $this->settings->firm_id,
      'u_id' => $this->user_id,
    ]);
      
    return redirect('/dashboard/cases')->with('status', 'Your case has been updated!');
    }
}
