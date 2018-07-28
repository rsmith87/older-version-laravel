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
use App\Media;
use App\MediaRelationship;
use App\Event;

class CaseController extends Controller
{

	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$this->user = \Auth::user();
			if (!$this->user) {
				return redirect('/login');
			}
			if (!$this->user->hasPermissionTo('view cases')) {
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			$this->cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('timers')->get();
			$this->contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 0])->get();
			$this->clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();

			$this->status_values = [
				'choose..',
				'potential',
				'active',
				'closed',
				'rejected'];

			$this->case_types = [
				'choose..',
				'estate_planning',
				'probate',
				'divorce',
				'child_custody',
				'child_support',
				'adoption',
				'name_changes',
				'criminal',
				'personal_injury',
				'real_estate',
				'bankruptcy',
				'immigration',
				'landlord/tenant',
				'social_security',
				'tax',
				'other'
			];

			$this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			$this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();


			return $next($request);
		});
	}

	public function index(Request $request)
	{
		if (!isset($this->settings->firm_id) || $this->settings->firm_id === 0) {
			return redirect('/dashboard/firm/')->with('status', 'You must provide your firm information before proceeding.');
		}

		$all_case_data = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => \Auth::id()])->with('contacts')->with('documents')->with('tasks')->get();
		$columns = [];
		$views = View::where(['u_id' => $this->user['id'], 'view_type' => 'case'])->get();
		$view_data_columns = [];
		if (count($views) > 0 && $views[0]->view_data != "") {
			foreach ($views as $view_data) {
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
		} else {
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
		if (count($timers) < 1) {
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

		(float)$minute_time_float = $minute_time / 60;
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




		$check = 0;
		if (isset($data['id'])) {
			$id = $data['id'];
			$check = 1;
		} else {
			$id = \DB::table('lawcase')->max('id') + 1;
			$check = 0;
		}


		if (isset($data['statute_of_limitations'])) {
			$date = new \DateTime();
			$date = $date->getTimestamp();
		} else {
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
				'open_date' => $data['open_date'] != "" ? $this->fix_date($data['open_date']) : "",
				'close_date' => $data['close_date'] != "" ? $this->fix_date($data['close_date']): "",
				'statute_of_limitations' => $date,
				'is_billable' => isset($data['rate']) ? "1" : "0",
				'billing_type' => isset($data['rate_type']) ? $data['rate_type'] : 'fixed',
				'billing_rate' =>  isset($data['billing_rate']) ? $data['billing_rate'] : 0.00,
				'firm_id' => $this->settings->firm_id,
				'u_id' => $this->user['id'],
				'user_id' => $this->user['id'],
				'case_uuid' => $case_uuid,
			]);

		$timer = Timer::where('user_id', $this->user['id'])->get();
		if ($timer === null) {
			$timer = [];
		} else {
			$timer = $timer->toArray();
		}

		if(isset($data['create_case_task_list'])){
			$tl_uuid = Uuid::generate()->string;

			$tl = TaskList::create([
				'task_list_uuid' => $tl_uuid,
				'task_list_name' => $data['name'],
				'c_id' => $id,
				'user_id' => \Auth::id(),
				'f_id' => $this->settings->firm_id,
			]);
		}

		if(isset($data['create_case_document_directory'])){
			$directory = \File::makeDirectory(public_path('files/user/'.\Auth::id().'/'.$this->clean($data['name'])));
		}

		if ($data['hours'] != "") {
			CaseHours::create([
				'case_uuid' => $case_uuid,
				'hours' => $data['hours'],
				'note' => "from case edit",
			]);
		}

		$timers = array_merge($timer, ['timers' => []]);
		return redirect('dashboard/cases/case/' . $case_uuid)->with('status', 'Case ' . $project->name . ' created successfully');

	}

	private function clean($string) {
		$string = str_replace(' ', '-', strtolower($string)); // Replaces all spaces with hyphens.

		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	public function add_timer(Request $request, $id)
	{
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
		//$all_media = [];
		$requested_case = LawCase::where(['firm_id' => $this->settings->firm_id, 'case_uuid' => $id])->with('contacts')->with('client')->with('documents')->first();
		if (count($requested_case) === 0) {
			return redirect('/dashboard/cases')->withErrors(['You don\'t have access to this case.']);
		}

		$media_relationships = MediaRelationship::where(['model_id' => $requested_case->id, 'model' => 'case'])->get();
		if (count($media_relationships) > 0) {
			foreach ($media_relationships as $mr) {
				$all_media[] = Media::where('uuid', $mr->media_uuid)->get();
			}
		}

		$case_hours = CaseHours::where('case_uuid', $id)->get();
		$hours_amount = '0';
		foreach ($case_hours as $ch) {
			$hours_amount += $ch->hours;
		}

		if ($requested_case->billing_type === 'fixed') {
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
		foreach ($invoices as $invoice) {
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
			'invoice_amount' => $invoice_amount,
			'case_hours' => $case_hours,

			'documents' => $documents,
			'media' => isset($all_media) ? $all_media : [],
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

	public function reference_client(Request $request)
	{
		$data = $request->all();

		$client = $data['client_id'];
		$case = $data['case_id'];
		$uuid = $data['case_uuid'];
		$client = Contact::where(['id' => $client, 'firm_id' => $this->settings->firm_id, 'is_client' => 1])->update(['case_id' => $case]);
		return redirect('/dashboard/cases/case/' . $uuid);
	}


	public function add_hours(Request $request)
	{
		$data = $request->all();

		$case = LawCase::where('case_uuid', $data['case_uuid'])->select(['billing_rate'])->first();

		CaseHours::create(['case_uuid' => $data['case_uuid'], 'hours' => number_format($data['hours_worked'], 2), 'note' => $data['hours_note']]);
		$hours_amount = '0';
		$case_hours = CaseHours::where('case_uuid', $data['case_uuid'])->get();

		foreach ($case_hours as $ch) {
			$hours_amount += $ch->hours;
		}

		$order = Order::where('case_uuid', $data['case_uuid'])->first();
		if (count($order) > 0) {
			Order::where('case_uuid', $data['case_uuid'])->update(['amount_remaining' => $order->amount_remaining + ($hours_amount * $case->billing_rate)]);
		}
		return redirect()->back()->with('status', 'Hours updated');
	}



	public function timeline($id)
	{

		//todo: create new timeline
		//complete: will need to get all objects into a singular array ordered by date //complete
		//complete: each item of the object will have an attribute determining the type of item (case added, hours added, etc)
		//then the view will colorize based on type

		//init array to create the data array
		$timeline_data = [];

		$requested_case = LawCase::where(['firm_id' => $this->settings->firm_id, 'case_uuid' => $id])->with('contacts')->with('client')->with('documents')->first();
		$case_hours = CaseHours::where('case_uuid', $id)->get();
		$case_notes = Note::where('case_uuid', $id)->get();
		$clients = Contact::where(['case_id' => $requested_case->id, 'is_client' => 1])->first();
		$contacts = Contact::where(['case_id' => $requested_case->id, 'is_client' => 0])->get();
		$order = Order::where('case_uuid', $id)->first();
		$documents = Document::where('case_id', $id)->get();
		$invoices = Invoice::where('invoicable_id', $id)->select('created_at', 'receiver_info', 'total')->get();
		$task_lists = TaskList::where('c_id', $id)->with('task')->get();
		$events = Event::where('c_id', $requested_case->id)->get();


		//init timeline data = cant have a case timeline without a case so it has to have this to be made
		$timeline_data[0]['date'] = $requested_case->created_at;
		$timeline_data[0]['headline'] = $requested_case->name;
		$timeline_data[0]['type'] = 'lawcase';
		$timeline_data[0]['link'] = '/dashboard/cases/case/'.$requested_case->case_uuid;


		if (count($clients) > 0) {
			$contact_created_time = $clients->created_at;
			array_push($timeline_data, [
				'date' => $contact_created_time,
				'headline' => 'Added ' . $clients->first_name . " " . $clients->last_name . ' as client.',
				'type' => 'client',
				'link' => '/dashboard/clients/client/'.$clients->contlient_uuid,
			]);
		}

		if (count($case_hours) > 0) {
			foreach ($case_hours as $case_hour) {
				if ($case_hour->hours != 0){
					$task_created_time = $case_hour->created_at;
				array_push($timeline_data, [
					'date' => $task_created_time,
					'headline' => $case_hour->note,
					'type' => 'hours',
					'link' => '/dashboard/cases/case/'.$requested_case->case_uuid,
				]);

				}
			}
		}

		if (count($case_notes) > 0) {
			foreach ($case_notes as $case_note) {
					$note_created_time = $case_note->created_at;
					array_push($timeline_data, [
						'date' => $note_created_time,
						'headline' => 'Added note ' . $case_note->note,
						'type' => 'note',
						'link' => '/dashboard/cases/case/'.$requested_case->case_uuid,
					]);


			}
		}

		if (count($events) > 0) {
			foreach ($events as $event) {
				$event_created_time = $event->created_at;
				array_push($timeline_data, [
					'date' => $event_created_time,
					'headline' => 'Added event ' . $event->name,
					'type' => 'event',
					'link' => '/dashboard/calendar',
				]);


			}
		}

		if (count($task_lists) > 0) {
			foreach ($task_lists as $task_list) {
				foreach ($task_list->Task as $task) {
					$task_created_time = $task->due;
					array_push($timeline_data, [
						'date' => $task_created_time,
						'headline' => 'Added ' . $task->task_name . ' as task.',
						'type' => 'tasklist',
						'link' => '/dashboard/tasklists/'.$task_list->task_list_uuid,
					]);
				}
			}
		}

		if (count($invoices) > 0) {
			foreach ($invoices as $invoice) {
				array_push($timeline_data, [
					'date' => $invoice->created_at,
					'headline' => 'Sent ' . $invoice->receiver_info . ' an invoice in the amount of $' . $invoice->total . '.',
					'type' => 'invoice',
					'link' => '/dashboard/invoices/invoice'.$invoice->invoice_uuid,
				]);
			}
		}

		if (count($documents) > 0) {
			foreach ($documents as $document) {
				$document_created_time = $document->created_at;
				array_push($timeline_data, [
					'date' => $document_created_time,
					'headline' => 'Added ' . $document->name . ' document.',
					'type' => 'document',
					'link' => '/dashboard/documents',
				]);
			}
		}

		if (count($contacts) > 0) {
			foreach ($contacts as $contact) {
				$contact_created_time = $contact->created_at;
				array_push($timeline_data, [
					'date' => $contact_created_time,
					'headline' => 'Added ' . $contact->first_name . " " . $contact->last_name . ' as contact.',
					'type' => 'contact',
					'link' => '/dashboard/contacts/contact/'.$contact->contlient_uuid,
				]);
			}
		}



		//complete: need to get all of the timeline data and order it by date
		usort($timeline_data, [$this, 'date_sort']);

		$invoices = Invoice::where('invoicable_id', $id)->get();

		$case_hours = CaseHours::where('case_uuid', $id)->get();
		$hours_amount = '0';
		foreach ($case_hours as $ch) {
			$hours_amount += $ch->hours;
		}

		if ($requested_case->billing_type === 'fixed') {
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
			'invoice_amount' => $invoice_amount,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'cases' => $this->cases,
			'contacts' => $this->contacts,
			'clients' => $this->clients,
			'case_uuid' => $requested_case->case_uuid,
			'documents' => $requested_case->Documents,
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

		if (array_key_exists('case_uuid', $data)) {
			$id = $data['case_uuid'];
		} else {
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

	public function hours_edit(Request $request)
	{
		$data = $request->all();

		$hours = CaseHours::where('id', $data['id'])->update(['hours' => $data['hours'], 'note' => $data['note']]);
		return redirect()->back()->with('status', 'Hours edited successfully');
	}

	public function hours_delete(Request $request)
	{
		$data = $request->all();

		$case_hours = CaseHours::where('id', $data['id'])->delete();
		return redirect()->back()->with('status', 'Hours deleted succesfully!');
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

	private static function date_sort($a, $b)
	{
		return strtotime($a['date']) - strtotime($b['date']);
	}
}
