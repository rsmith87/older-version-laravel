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
use App\Task;
use App\CaseHours;
use App\Note;
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
      $this->clients = Contact::where(['user_id' => $this->user['id'], 'is_client' => 1])->first();
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
 
    if(isset($data['id'])){
      $id = $data['id'];
    } else {
      $id = \DB::table('case')->max('id') + 1;
    }
    
    if(isset($data['hours'])){
      CaseHours::create([
       'case_id' => $id,
       'hours' => $data['hours'],
        'note' => "from case edit"
      ]);
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
      'firm_id' => $this->settings->firm_id,
      'u_id' => \Auth::id(),
    ]);
    
      
    return redirect('/dashboard/cases/case/'.$id)->with('status', 'Case '.$data['name'].' has been updated!');
    }
  
  
  
  public function case($id, Request $request)
  {
   
    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id])->with('contacts')->with('client')->with('documents')->first();
    if(count($requested_case) === 0){
      return redirect('/dashboard/cases')->withErrors(['You don\'t have access to this case.']);
    }
    
    $case_hours = CaseHours::where('case_id', $id)->get();
    $hours_amount = '0';
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }
  
    if($requested_case->billing_type === 'fixed'){
      $invoice_amount = $requested_case->billing_rate;
    } else {
      $invoice_amount = $requested_case->billing_rate * $hours_amount;
    }
    $clients = Contact::where(['case_id' => $id, 'is_client' => 1])->first();
    $client_id = Contact::where(['case_id' => $id, 'is_client' => 1])->select('id')->first();
    $order = Order::where('case_id', $id)->first();
    $invoices = Invoice::where('invoicable_id', $id)->get();
    $documents = Document::where('case_id', $id)->get();
    $notes = Note::where('case_id', $id)->get();
    foreach($invoices as $invoice){
      $line = InvoiceLine::where('invoice_id', $invoice->id)->select('amount')->first();
      $invoice_amount = $invoice_amount - $line->amount;
    }
    //print_r($notes);
   

    return view('dashboard.case', [
      'user_name' => $this->user['name'],
      'case' => $requested_case,
      'hours_worked' => $hours_amount,
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
      'notes' => $notes,
    ]);
    
  }
  
  
  public function add_hours(Request $request)
  {
    $data = $request->all();
    $case = LawCase::where('id', $data['case_id'])->select(['billing_rate'])->first();

    CaseHours::create(['case_id' => $data['case_id'], 'hours' => number_format($data['hours_worked'], 2), 'note' => $data['hours_note']]);
    $case_hours = CaseHours::where('case_id', $data['case_id'])->get();
    $hours_amount = '0';
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }

    $order = Order::where('case_id', $data['case_id'])->first();
    if(count($order) > 0){
     Order::where('case_id', $data['case_id'])->update(['amount_remaining' => $order->amount_remaining + ($hours_amount * $case->billing_rate)]);
    }
    return redirect('/dashboard/cases/case/'.$data['case_id'])->with('status', 'Hours updated');
  }
  
  public function timeline($id)
  {   
    $timeline_data = [];
    $timeline_data['timeline'] = [];    
    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'id' => $id])->with('contacts')->with('client')->with('documents')->first();
    $case_hours = CaseHours::where('case_id', $id)->get();
    $clients = Contact::where(['case_id' => $id, 'is_client' => 1])->first();
    $contacts =  Contact::where(['case_id' => $id, 'is_client' => 0])->get();
    $order = Order::where('case_id', $id)->first();
    $documents = Document::where('case_id', $id)->get();
    $invoices = Invoice::where('invoicable_id', $id)->select('created_at', 'receiver_info', 'total')->get();
    $tasks = Task::where('c_id', $id)->get();
    $created_time = $requested_case->created_at;
    $case_created_time = $created_time->toDateTimeString();
    $case_added_hour = $requested_case->created_at->addHour();
   


    $timeline_data['timeline']['date'][0] = [
      'startDate' => $this->setup_date_timeline($case_created_time),
      'endDate' => $this->setup_date_timeline($case_added_hour),
      'headline' => 'Case created',
      'text' => 'Case created on Legalkeeper!',
      'tag' => '',
      'classname' => '',
    ];

    $timeline_data['timeline']['date'][0]['asset'] = [
      'media' => 'http://legality-codenerd33.codeanyapp.com/img/case-background.png',
      'credit' => '',
      'caption' => '',
    ];    

    $timeline_data['timeline']['asset'] = [
      'media' => 'http://legality-codenerd33.codeanyapp.com/img/logo-long-black.png',
      'credit' => 'CREDIT NAME GOES HERE',
      'caption' => 'CAPTION TEST',
    ];
 

   
    if(count($clients) > 0){
      $contact_created_time = $this->setup_date_timeline($clients->created_at);
      $contact_added_hour = $this->setup_date_timeline($clients->created_at->addHour());

      array_push($timeline_data['timeline']['date'],  [
        'startDate' => $this->setup_date_timeline($contact_created_time),
        'endDate' => $this->setup_date_timeline($contact_added_hour),
        'headline' => 'Added ' . $clients->first_name . " " . $clients->last_name . ' as client.',
        'text' => 'Client added!',
        'tag' => '',
        'classname' => '', 
        'asset' => [
          'media' => 'http://legality-codenerd33.codeanyapp.com/img/clients-background.png',
          'credit' => '',
          'caption' => '',         
        ]
      ]);
    }
    
    if(count($case_hours) > 0){
 
      foreach($case_hours as $case_hour){

        $task_created_time = $this->setup_date_timeline($case_hour->created_at);
        $task_added_hour = $this->setup_date_timeline(Carbon\Carbon::parse($case_hour->created_at)->addHour());

        array_push($timeline_data['timeline']['date'], [
          'startDate' => $this->setup_date_timeline($task_created_time),
          'endDate' => $this->setup_date_timeline($task_added_hour),
          'headline' => 'Worked '. $case_hour->hours . ' hours on case.',
          'text' => $case_hour->hours . ' hours worked.',
          'tag' => '',
          'classname' => '',       
          'asset' => [
            'media' => 'http://legality-codenerd33.codeanyapp.com/img/logo-long-black.png',
            'credit' => '',
            'caption' => 'You added a task to this case.',
          ]
        ]);





      }  
    } 


  
    if(count($tasks) > 0){
 
      foreach($tasks as $task){

        $task_created_time = $this->setup_date_timeline($task->due);
        $task_added_hour = $this->setup_date_timeline(Carbon\Carbon::parse($task->due)->addHour());

        array_push($timeline_data['timeline']['date'], [
          'startDate' => $this->setup_date_timeline($task_created_time),
          'endDate' => $this->setup_date_timeline($task_added_hour),
          'headline' => 'Added ' . $task->task_name . ' as task.',
          'text' => $task->task_description,
          'tag' => '',
          'classname' => '',       
          'asset' => [
            'media' => 'http://legality-codenerd33.codeanyapp.com/img/logo-long-black.png',
            'credit' => '',
            'caption' => 'You added a task to this case.',
          ]
        ]);





      }  
    }

       //print_r(count($timeline_data['timeline']['date']));
    if(count($invoices) > 0){
      foreach($invoices as $invoice){

        array_push($timeline_data['timeline']['date'], [
          'startDate' =>  $this->setup_date_timeline($invoice->created_at),
          'endDate' => $this->setup_date_timeline($invoice->created_at),
          'headline' => 'Sent ' . $invoice->receiver_info . ' an invoice in the amount of $'. $invoice->total .'.',
          'text' => 'Invoice created for '. $invoice->receiver_info .'!',
          'tag' => '',
          'classname' => '',  
          'asset' => [
            'media' => 'http://legality-codenerd33.codeanyapp.com/img/logo-long-black.png',
            'credit' => '',
            'caption' => '',         
          ]
        ]);



      }        
    }


    if(count($documents) > 0){
    
      foreach($documents as $document){

        $document_created_time = $this->setup_date_timeline($document->created_at);

        array_push($timeline_data['timeline']['date'], [
          'startDate' => $document_created_time,
          'endDate' => $document_created_time,
          'headline' => 'Added ' . $document->name . ' document.',
          'text' => 'Document added!',
          'tag' => '',
          'classname' => '',    
          'asset' => [
            'media' => 'https://s3.amazonaws.com/legaleeze'.$document->path,
            'credit' => '',
            'caption' => '',
          ]
        ]);


      }
    }
    
   

    if(count($contacts) > 0){
      foreach($contacts as $contact){

        // print_r($i);
        $contact_created_time = $this->setup_date_timeline($contact->created_at);
        $contact_added_hour = $this->setup_date_timeline($contact->created_at);

        array_push($timeline_data['timeline']['date'], [
          'startDate' => $contact_created_time,
          'endDate' => $contact_added_hour,
          'headline' => 'Added ' . $contact->first_name . " " . $contact->last_name . ' as contact.',
          'text' => 'Contact added!',
          'tag' => '',
          'classname' => '',   
          'asset' => [
            'media' => 'http://legality-codenerd33.codeanyapp.com/img/contacts-background.png',
            'credit' => '',
            'caption' => '',
          ]
        ]);
      }
    }
    
    // print_r($timeline_data['timeline']);
    $timeline_data['timeline']['headline'] = '';
    $timeline_data['timeline']['type'] = "default";
    $timeline_data['timeline']['text'] = "";




   // print_r($invoices);

    $invoices = Invoice::where('invoicable_id', $id)->get();
    
    $case_hours = CaseHours::where('case_id', $id)->get();
    $hours_amount = '0';
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }
    
    if($requested_case->billing_type === 'fixed'){
      $invoice_amount = $requested_case->billing_rate;
    } else {
      $invoice_amount = $requested_case->billing_rate * $hours_amount;
    }
    
    return view('dashboard.timeline', [
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
    'documents' =>$requested_case->Documents,
    'timeline_data' => $timeline_data,
    ]);
  }
  
  public function note_add(Request $request)
  {
    $data = $request->all();
    
    if(array_key_exists('id', $data)){
      $id = $data['id'];
    }
    else {
      $id = \DB::table('case')->max('id') + 1;
    }
    
    $note = Note::create([
      'case_id' => $data['case_id'],
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
  
  private function fix_date($dts)
  {
    $d = Carbon\Carbon::parse($dts)->format('Y-m-d');
    $dt = Carbon\Carbon::parse($dts . " " . '00:00:00', 'America/Chicago')->format('Y-m-d H:i:s');
    return $dt;
  }
  
  function setup_date_timeline($date)
  {
    $javascript_needed_time = str_replace('-', ',', $date);
    $javascript_needed_time = str_replace(':', ',', $javascript_needed_time);
    $javascript_needed_time = str_replace(' ', ',', $javascript_needed_time);
    return $javascript_needed_time;
  }
}
