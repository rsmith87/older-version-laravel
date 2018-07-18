<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\LawCase;
use App\Contact;
use App\Settings;
use App\View;
use App\Timer;
use App\Order;
use App\Document;
use App\Invoice;
use App\InvoiceLine;
use App\TaskList;
use App\Thread;
use App\CaseHours;
use App\Note;
use App\FirmStripe;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;

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
      $this->cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('timers')->get();
      $this->contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 0])->get();
      $this->clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
      $this->status_values = ['choose..', 'potential', 'active', 'closed', 'rejected'];
      $this->case_types = ['choose..' , 'personal_injury', 'estate_and_probate'];
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
      
      
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
    
    
    $cases = LawCase::where(["firm_id" => $this->settings->firm_id, 'u_id' => \Auth::id()])->select($columns)->with('timers')->with('contacts')->with('documents')->get();
    

    
    return view('dashboard/cases', 
    [
      'user' => $this->user,
      'cases' => $cases,
      'columns' => $columns,
      'firm_id' => $this->settings->firm_id,
      'theme' => $this->settings->theme,
      'status_values' => $this->status_values,
      'case_types' => $this->case_types,
      'all_case_data' => $all_case_data,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,
      'threads' => $this->threads,
    ]);
  }
  
  public function timer_cases(Request $request)
  {
     $projects = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('timers')->get()->toArray();
     
     return $projects;
  }
  
  public function timer_for_case(Request $request, $id)
  {
      $projects = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id(), 'case_uuid' => $id])->with('timers')->get()->toArray();
      
      return $project;
   
  }
  
  public function timers_active(Request $request)
  {
    $timers = Timer::where(['user_id' => \Auth::id(), 'stopped_at' => null])->with('lawcase')->first();
    if(count($timers) < 1){
      $timers = [];
    }
    return $timers;
  }
  
  public function stop_timer()
  {

    if ($timer = Timer::where(['user_id' => \Auth::id(), 'stopped_at' => null])->first()) {
        $timer->update(['stopped_at' => new Carbon]);
    }

    $started = new \DateTime($timer->started_at);
    $stopped = new \DateTime($timer->stopped_at);
    $diff = $stopped->diff($started);
    $hour_time = $diff->format('%h');
    $minute_time = $diff->format('%m');
    $second_time = $diff->format('%s');
    
    (float)$minute_time_float = $minute_time/60;
    //convert seconds to match a 100 scale rather than 60 so it can match with the format in the database
    
    $time_to_add = $hour_time . "." . $minute_time;
    
    //test this more becace the house didn't come through - something messed up
    //UPDATE CASE HOURS TABLE HERE
    $case_hours = CaseHours::insert([
        'case_uuid' => $timer->law_case_id,
        'hours' => (float)$time_to_add,
        'note' => 'from app timer',
    ]);
    return $timer;
        
  }

  public function case_timers(Request $request)
  {
     $projects = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('timers')->get();  
     return $projects;
  }
  
  public function delete(Request $request)
  {
    $data = $request->all();
    $case = LawCase::where('case_uuid', $data['case_uuid'])->delete();
    return redirect()->back()->with('status', 'Case deleted successfully');
  }
  
  public function timer_store(Request $request, $id)
  {
      $data = $request->validate(['name' => 'required|between:3,100']);
        
      
      $timer = Timer::insert([
          'name' => $data['name'],
          'user_id' => \Auth::id(),
          'started_at' => new Carbon,
          'created_at' => new Carbon,
          'law_case_id' => $id,
      ]);
      
      $timer = Timer::where(['name' => $data['name'], 'law_case_id' => $id])->with('lawcase')->first();
      

      return $timer;
  }
  
  public function add(Request $request)
  {
    $data = $request->all();
    

    // returns validated data as array
    //$vue_data = $request->validate(['name' => 'required|between:2,20']);
   
      $check = 0;
    if(isset($data['id'])){
      $id = $data['id'];
      $check = 1;
    } else {
      $id = \DB::table('lawcase')->max('id') + 1;
      $check = 0;
    }
    

    
    if(isset($data['statute_of_limitations'])){
      $date = new \DateTime();
      $date = $date->getTimestamp();
    }
    else {
      $date = "";
    }
    
    $case_uuid = Uuid::generate()->string;
      
    $project = LawCase::updateOrCreate(
    [
      'id' => $id, 
    ],
    [
      'status' => $data['status'], 
      'type' => $data['type'],
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
      'u_id' => $this->user['id'],
      'user_id' => $this->user['id'],
      'case_uuid' => $case_uuid,
    ]);
    
    $timer = Timer::where('user_id', $this->user['id'])->get();
    if($timer === null){
      $timer = [];
    } else {
      $timer = $timer->toArray();
    }
    
    if(isset($data['hours'])){
      CaseHours::create([
       'case_uuid' => $case_uuid,
       'hours' => $data['hours'],
        'note' => "from case edit"
      ]);
    }
    
    $timers = array_merge($timer, ['timers' => []]);
    return redirect('dashboard/cases/case/'.$case_uuid)->with('status', 'Case '. $project->name . ' created successfully'); 
            
    }
  
  public function add_timer(Request $request, $id){
    $data = $request->all();
    $timer = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->findOrFail($id)
              ->timers()
              ->save(new Timer(
                [
                  'name' => $data['name'],
                  'user_id' => \Auth::id(),
                  'started_at' => new Carbon(),
                ]
              )
              );
   return $timer ? array_merge($timer->toArray(), ['timers' => []]) : false;
  }
  
    public function stopRunning()
  {
   if ($timer = Timer::where('user_id', \Auth::id())->with('lawcase')->running()->first()) {
     $timer->update(['stopped_at' => new Carbon()]);
   }
  return $timer;
  }
  
  public function running()
  {
    return Timer::where('user_id', \Auth::id())->with('lawcase')->running()->first() ?? [];
  }
  
  public function lawcase($id, Request $request)
  {
    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'case_uuid' => $id])->with('contacts')->with('client')->with('documents')->first();
    if(count($requested_case) === 0){
      return redirect('/dashboard/cases')->withErrors(['You don\'t have access to this case.']);
    }
    
    $case_hours = CaseHours::where('case_uuid', $id)->get();
    $hours_amount = '0';
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }
  
    if($requested_case->billing_type === 'fixed'){
      $invoice_amount = $requested_case->billing_rate;
    } else {
      $invoice_amount = $requested_case->billing_rate * $hours_amount;
    }
    
    $client_id = Contact::where(['case_id' => $id, 'is_client' => 1])->select('id')->first();
    $order = Order::where('case_uuid', $id)->first();
    $invoices = Invoice::where('invoicable_id', $id)->get();
    $documents = Document::where('case_id', $id)->get();
    $notes = Note::where('case_uuid', $id)->get();
    $task_lists = TaskList::where('c_id', $id)->with('task')->get();
    foreach($invoices as $invoice){
      $line = InvoiceLine::where('invoice_id', $invoice->id)->select('amount')->first();
      $invoice_amount = $invoice_amount - $line->amount;
    }
   
    $project = $requested_case;
    $project = $project ? array_merge($project->toArray(), ['timers' => []]) : false;
    
    return view('dashboard.case', [
      'user' => $this->user,
      
      'case' => $requested_case,
        
      'contacts' => $this->contacts,
      'cases' => $this->cases,
      'clients' => $this->clients,

      
      'project' => $project,  
      'hours_worked' => $hours_amount,
      'order' => $order,
      'status_values' => $this->status_values,
      'case_types' => $this->case_types,        
      'invoice_amount' =>$invoice_amount, 

      'documents' => $documents,
      'notes' => $notes,
      'task_lists' => $task_lists,
      'firm_id' => $this->settings->firm_id,
       
      'theme' => $this->settings->theme,
      'table_color' => $this->settings->table_color,
      'table_size' => $this->settings->table_size, 
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,   
      'threads' => $this->threads,
    ]);
    
  }
  
  public function reference_client(Request $request){
    $data = $request->all();
    
    $client = $data['client_id'];
    $case = $data['case_id'];
    $uuid = $data['case_uuid'];
    $client = Contact::where(['id' => $client, 'firm_id' => $this->settings->firm_id, 'is_client' => 1])->update(['case_id' => $case]);
    return redirect('/dashboard/cases/case/'.$uuid);
  }
  
  
  public function add_hours(Request $request)
  {
    $data = $request->all();
    
    $case = LawCase::where('case_uuid', $data['case_uuid'])->select(['billing_rate'])->first();

    CaseHours::create(['case_uuid' => $data['case_uuid'], 'hours' => number_format($data['hours_worked'], 2), 'note' => $data['hours_note']]);
    $hours_amount = '0';
    $case_hours = CaseHours::where('case_uuid', $data['case_uuid'])->get();
    
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }

    $order = Order::where('case_uuid', $data['case_uuid'])->first();
    if(count($order) > 0){
     Order::where('case_uuid', $data['case_uuid'])->update(['amount_remaining' => $order->amount_remaining + ($hours_amount * $case->billing_rate)]);
    }
    return redirect('/dashboard/cases/case/'.$data['case_uuid'])->with('status', 'Hours updated');
  }
  
  public function timeline($id)
  {

	  //todo: create new timeline
	  //will need to get all objects into a singular array ordered by date
	  //each item of the object will have an attribute determining the type of item (case added, hours added, etc)
	  //then the view will colorize based on type
  	//init array to create the data array
    $timeline_data = [];

    $requested_case = LawCase::where(['firm_id' =>  $this->settings->firm_id, 'case_uuid' => $id])->with('contacts')->with('client')->with('documents')->first();
    $case_hours = CaseHours::where('case_uuid', $id)->get();
    $clients = Contact::where(['case_id' => $id, 'is_client' => 1])->first();
    $contacts =  Contact::where(['case_id' => $id, 'is_client' => 0])->get();
    $order = Order::where('case_uuid', $id)->first();
    $documents = Document::where('case_id', $id)->get();
    $invoices = Invoice::where('invoicable_id', $id)->select('created_at', 'receiver_info', 'total')->get();
    $task_lists = TaskList::where('c_id', $id)->with('task')->get();

    $timeline_data[0]['date'] = $requested_case->created_at;

    $created_time = $requested_case->created_at;
    $case_created_time = $created_time->toDateTimeString();
    $case_added_hour = $requested_case->created_at->addHour();




    if(count($clients) > 0){
      $contact_created_time = $clients->created_at;
      $contact_added_hour = $clients->created_at->addHour();

      array_push($timeline_data,  [
        'startDate' => $contact_created_time,
        'endDate' => $contact_added_hour,
        'headline' => 'Added ' . $clients->first_name . " " . $clients->last_name . ' as client.',
        'text' => 'Client added!',
        'tag' => '',
        'classname' => '',
        'asset' => [
          'media' =>  '/img/clients-background.png',
          'credit' => '',
          'caption' => '',
        ]
      ]);
    }

    if(count($case_hours) > 0){

      foreach($case_hours as $case_hour){

        $task_created_time = $case_hour->created_at;
        $task_added_hour = Carbon::parse($case_hour->created_at)->addHour();

        array_push($timeline_data, [
          'startDate' => $task_created_time,
          'endDate' => $task_added_hour,
          'headline' => 'Worked '. $case_hour->hours . ' hours on case.',
          'text' => $case_hour->hours . ' hours worked.',
          'tag' => '',
          'classname' => '',
          'asset' => [
            'media' => '/img/logo-long-black.png',
            'credit' => '',
            'caption' => 'You added a task to this case.',
          ]
        ]);





      }
    }



    if(count($task_lists) > 0){

      foreach($task_lists as $task_list){

      foreach($task_list->Task as $task){
        $task_created_time = $task->due;
        $task_added_hour = Carbon::parse($task->due)->addHour();

        array_push($timeline_data, [
          'startDate' => $task_created_time,
          'endDate' => $task_added_hour,
          'headline' => 'Added ' . $task->task_name . ' as task.',
          'text' => $task->task_description,
          'tag' => '',
          'classname' => '',
          'asset' => [
            'media' =>  '/img/logo-long-black.png',
            'credit' => '',
            'caption' => 'You added a task to this case.',
          ]
        ]);

        }
    }



      }


       //print_r(count($timeline_data['timeline']['date']));
    if(count($invoices) > 0){
      foreach($invoices as $invoice){

        array_push($timeline_data, [
          'startDate' =>  $invoice->created_at,
          'endDate' => $invoice->created_at,
          'headline' => 'Sent ' . $invoice->receiver_info . ' an invoice in the amount of $'. $invoice->total .'.',
          'text' => 'Invoice created for '. $invoice->receiver_info .'!',
          'tag' => '',
          'classname' => '',
          'asset' => [
            'media' =>  '/img/logo-long-black.png',
            'credit' => '',
            'caption' => '',
          ]
        ]);



      }
    }


    if(count($documents) > 0){

      foreach($documents as $document){

        $document_created_time = $document->created_at;

        array_push($timeline_data, [
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
        $contact_created_time = $contact->created_at;
        $contact_added_hour = $contact->created_at;

        array_push($timeline_data, [
          'startDate' => $contact_created_time,
          'endDate' => $contact_added_hour,
          'headline' => 'Added ' . $contact->first_name . " " . $contact->last_name . ' as contact.',
          'text' => 'Contact added!',
          'tag' => '',
          'classname' => '',
          'asset' => [
            'media' =>  '/img/contacts-background.png',
            'credit' => '',
            'caption' => '',
          ]
        ]);
      }
    }





   // print_r($invoices);

    $invoices = Invoice::where('invoicable_id', $id)->get();

    $case_hours = CaseHours::where('case_uuid', $id)->get();
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
	    'user' => $this->user,
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
	    'settings' => $this->settings,
	    'fs' => $this->firm_stripe,
	    'threads' => $this->threads,
	   'case_types' => $this->case_types,
    ]);
  }
  
  public function note_add(Request $request)
  {
    $data = $request->all();
    
    if(array_key_exists('case_uuid', $data)){
      $id = $data['case_uuid'];
    }
    else {
      $id = "";
    }
    
    $note = Note::create([
      'case_uuid' => $id,
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
    $d = Carbon::parse($dts)->format('Y-m-d');
    $dt = Carbon::parse($dts . " " . '00:00:00', 'America/Chicago')->format('Y-m-d H:i:s');
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
