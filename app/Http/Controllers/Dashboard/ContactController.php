<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contact;
use App\User;
use App\View;
use App\LawCase;
use App\Note;
use App\Settings;
use App\TaskList;
use App\CommLog;
use App\FirmStripe;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\Media;
use App\MediaRelationship;

class ContactController extends Controller
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
			if (!$this->user->hasPermissionTo('view contacts')) {
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			$this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			//$this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();

			return $next($request);
		});
	}

	public function index(Request $request)
	{
		$columns = [];
		$view_data_columns = [];
		$views = View::where(['u_id' => $this->user['id'], 'view_type' => 'contact'])->get();

		if (count($views) > 0 && $views[0]->view_data != "") {
			foreach ($views as $view_data) {
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
			//array_unshift($columns, 'contlient_uuid');
		} else {
			$columns = ["contlient_uuid", "first_name", "last_name", "phone", "email"];
		}

		$array_cases = [];
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
		foreach ($cases as $case) {
			$array_cases[$case->id] = $case->name;
		}

		$contacts = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0', 'user_id' => $this->user['id']])->select($columns)->with('tasks')->get();
		$other_data = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0', 'user_id' => $this->user['id']])->get();

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


		]);
	}

	public function contact(Request $request, $id)
	{
		$requested_contact = Contact::where(['firm_id' => $this->settings->firm_id, 'contlient_uuid' => $id, 'user_id' => $this->user['id']])->first();

		if (!$requested_contact) {
			return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
		}

		$media_relationships = MediaRelationship::where(['model_id' => $requested_contact->id, 'model' => 'contact'])->get();
		if (count($media_relationships) > 0) {
			foreach ($media_relationships as $mr) {
				$all_media[] = Media::where('uuid', $mr->media_uuid)->get();
			}
		}

		$cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->get();
		$logs = CommLog::where(['type' => 'contact_client', 'type_id' => $requested_contact->id])->get();
		$task_lists = TaskList::where('contact_client_id', $id)->with('task')->get();
		$notes = Note::where('contlient_uuid', $requested_contact->contlient_uuid)->get();
		$case = LawCase::where(['firm_id' => $this->settings->firm_id, 'id' => $requested_contact->case_id])->first();

		return view('dashboard.contact', [
			'user' => $this->user,
			'cases' => $cases,
			'case' => $case,
			'contact' => $requested_contact,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'media' => isset($all_media) ? $all_media : [],
			'is_client' => 0,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'notes' => $notes,
			'task_lists' => $task_lists,
			'logs' => $logs,
			'settings' => $this->settings,
		]);
	}

	public function client(Request $request, $id)
	{
		$requested_contact = Contact::where(['firm_id' => $this->settings->firm_id, 'contlient_uuid' => $id, 'is_client' => '1', 'user_id' => $this->user['id']])->with('tasks')->first();

		if (!$requested_contact) {
			return redirect('/dashboard/contacts')->withError('You don\'t have access to this case.');
		}
		$all_media = [];
		$media_relationships = MediaRelationship::where(['model_id' => $requested_contact->id, 'model' => 'client'])->get();
		if (count($media_relationships) > 0) {
			foreach ($media_relationships as $mr) {
				$all_media[] = Media::where('uuid', $mr->media_uuid)->get();
			}
		}

		$cases = LawCase::where(['firm_id' => $this->settings->firm_id, 'u_id' => $this->user['id']])->get();
		$notes = Note::where('contlient_uuid', $id)->get();
		//$notes = [];
		$task_lists = TaskList::where('contact_client_id', $id)->with('task')->get();
		$logs = CommLog::where(['type' => 'contact_client', 'type_id' => $requested_contact->id])->get();

		$case = LawCase::where(['firm_id' => $this->settings->firm_id, 'id' => $requested_contact->case_id])->first();

		return view('dashboard.contact', [
			'user' => $this->user,
			'contact' => $requested_contact,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'case' => $case,
			'media' => $all_media,
			'is_client' => 1,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'notes' => $notes,
			'task_lists' => $task_lists,
			'logs' => $logs,
			'settings' => $this->settings,
		]);
	}

	public function firm_contacts(Request $request)
	{
		$firm_clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 0])->get();

		$columns = [];
		$views = View::where(['u_id' => $this->user['id'], 'view_type' => 'contact'])->get();

		$view_data_columns = [];

		if (count($views) > 0 && $views[0]->view_data != "") {
			foreach ($views as $view_data) {
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
			//array_unshift($columns, 'contlient_uuid');
		} else {
			$columns = ["contlient_uuid", "first_name", "last_name", "phone", "email"];
		}

		$array_cases = [];
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();

		foreach ($cases as $case) {
			$array_cases[$case->id] = $case->name;
		}


		$contacts = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0'])->select($columns)->with('documents')->with('tasks')->get();
		$other_data = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0'])->get();


		return view('dashboard/contacts', [
			'user' => $this->user,
			'columns' => $columns,
			'views' => $views,
			'contacts' => $firm_clients,
			'other_data' => $other_data,
			'user_name' => $this->user['name'],
			'cases' => $cases,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'array_cases' => $array_cases,
			'settings' => $this->settings,


		]);
	}

	public function firm_clients(Request $request)
	{
		$firm_clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();

		$columns = [];
		$views = View::where(['u_id' => $this->user['id'], 'view_type' => 'contact'])->get();

		$view_data_columns = [];

		if (count($views) > 0 && $views[0]->view_data != "") {
			foreach ($views as $view_data) {
				$data = $view_data->view_data;
			}
			$columns = json_decode($data, true);
			//array_unshift($columns, 'contlient_uuid');
		} else {
			$columns = ["contlient_uuid", "first_name", "last_name", "phone", "email"];
		}

		$array_cases = [];
		$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();

		foreach ($cases as $case) {
			$array_cases[$case->id] = $case->name;
		}


		$contacts = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0'])->select($columns)->with('documents')->with('tasks')->get();
		$other_data = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '0'])->get();


		return view('dashboard/contacts', [
			'user' => $this->user,
			'columns' => $columns,
			'views' => $views,
			'contacts' => $firm_clients,
			'other_data' => $other_data,
			'user_name' => $this->user['name'],
			'cases' => $cases,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'array_cases' => $array_cases,
			'settings' => $this->settings,


		]);
	}

	public function relate(Request $request)
	{
		$data = $request->all();

		$uuid = $data['contlient_uuid'];
		$update = Contact::where('contlient_uuid', $uuid)->update(['case_id' => $data['case_id']]);
		return redirect()->back()->with('status', 'Case connected!');
	}

	public function clients(Request $request)
	{
		$columns = [];
		$views = View::where(['u_id' => $this->user->id, 'view_type' => 'client'])->get();

		$cases = LawCase::where('user_id', \Auth::id())->get();

		$view_data_columns = [];


		$columns = ["contlient_uuid", "first_name", "last_name", "phone", "email"];


		$cases = LawCase::where('firm_id', $this->settings->firm_id)->select('id', 'name')->get();
		$contacts = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '1', 'user_id' => $this->user['id']])->select($columns)->with('tasks')->get();
		$other_data = Contact::where(["firm_id" => $this->settings->firm_id, 'is_client' => '1'])->get();

		return view('dashboard/clients', [
			'user' => $this->user,
			'columns' => $columns,
			'views' => $views,
			'cases' => $cases,
			'contacts' => $contacts,
			'other_data' => $other_data,
			'user_name' => $this->user['name'],
			'cases' => $cases,
			'firm_id' => $this->settings->firm_id,
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'settings' => $this->settings,
		]);
	}

	public function add(Request $request)
	{


		$validatedData = $request->validate([
			'first_name' => 'required',
			'last_name' => 'required',
		]);

		$data = $request->all();
		$user = $this->user;
		$firm = $this->settings->firm_id;

		if (!isset($data['id'])) {
			$data['id'] = \DB::table('contact')->max('id') + 1;
			$updated = 'added';
		} else {
			$updated = 'updated';
		}

		if (!isset($data['case_id']) || $data['case_id'] === "") {
			$data['case_id'] = 0;
		}
		if (!isset($data['relationship'])) {
			$data['relationship'] = "";
		}
		$contlient_uuid = Uuid::generate()->string;

		if (empty($data['is_client']) or !isset($data['is_client']) || $data['is_client'] === '0') {
			$redirect = 'dashboard/contacts/contact/'.$contlient_uuid;
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

		} else if ($data['is_client'] === '1') {
			$redirect = 'dashboard/clients/client/'.$contlient_uuid;
			$type = 'Client';

			$case = LawCase::where('id', $data['case_id'])->first();

			if($data['case_id'] != 0) {
				$more_than_one_client = Contact::where(['is_client' => 1, 'case_id' => $case->id])->get();
			} else {
				$more_than_one_client = [];
			}

			if (count($more_than_one_client) < 1) {

				Contact::updateOrCreate(
					[
						'id' => $data['id'],
					],
					[
						'case_id' => isset($data['case_id']) ? $data['case_id'] : 0,
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
						'is_client' => '1',
					]);

			} else {
				return redirect()->back()->withErrors(['A case can only have one client.']);
			}
		}


		$status = $type . " " . $data['first_name'] . " " . $data['last_name'] . " " . $updated . "!";

		return redirect()->back()->with('status', $status);

	}

	public function delete(Request $request)
	{
		$data = $request->all();

		$contact = Contact::where('id', $data['id'])->first();

		if ($contact->is_client != 0) {
			$type = 'Client';
		} else {
			$type = 'Contact';
		}
		$contact->delete();

		return redirect()->back()->with('status', $type . ' deleted');
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

		return back()->with('status', 'Note added');

	}

	public function note_edit(Request $request)
	{
		$data = $request->all();

		$note = Note::where('id', $data['id'])->update(['note' => $data['note']]);
		return redirect()->back()->with('status', 'Note edited');
	}

	public function note_delete(Request $request)
	{
		$data = $request->all();

		$note = Note::where('id', $data['id'])->delete();
		return redirect()->back()->with('status', 'Note deleted');
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
