<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\FirmStripe;
use App\Lead;
use Webpatser\Uuid\Uuid;
use App\CommLog;
use App\Note;


class LeadController extends Controller
{
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

        $leads = Lead::where('user_id', \Auth::id())->get();

        return view('dashboard/leads', [
            'user' => $this->user,
            'leads' => $leads,
            'firm_id' => $this->settings->firm_id,
            'theme' => $this->settings->theme,
            'table_color' => $this->settings->table_color,
            'table_size' => $this->settings->table_size,
            'settings' => $this->settings,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->all();
        $lead_uuid = Uuid::generate()->string;
        Lead::create(
        [
            'lead_uuid' => $lead_uuid,
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
        ]);
        return redirect()->back()->with('status', 'Lead created successfully');
    }

    public function view(Request $request, $id)
    {

        $lead = Lead::where('lead_uuid', $id)->first();
        $logs = CommLog::where(['type' => 'lead', 'type_id' => $lead->lead_uuid])->get();
        $notes = Note::where('lead_uuid', $id)->get();

        return view('dashboard/lead', [
            'user' => $this->user,
            'lead' => $lead,
            'logs' => $logs,
            'notes' => $notes,
            'firm_id' => $this->settings->firm_id,
            'theme' => $this->settings->theme,
            'table_color' => $this->settings->table_color,
            'table_size' => $this->settings->table_size,
            'settings' => $this->settings,
        ]);
    }

    public function add_note(Request $request)
    {
        $data = $request->all();

        $note = Note::create([
            'lead_uuid' => $data['lead_uuid'],
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

        $type_id = $data['lead_id'];

        $log = CommLog::create([
            'type' => 'lead',
            'type_id' => $type_id,
            'comm_type' => $data['communication_type'],
            'log' => $data['communication'],
        ]);

        return redirect()->back()->with('status', 'Communication logged!');

    }
}
