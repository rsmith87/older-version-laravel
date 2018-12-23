<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\FormCompleted;
use App\Settings;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;


class FormController extends Controller
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
            if (!$this->user) {
                return redirect('/login');
            }
            if (!$this->user->hasPermissionTo('view documents')) {
                return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
            }
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
    }

    public function index()
    {
        $forms = Form::where('user_id', $this->user['id'])->get();
        return view('dashboard.forms', [
            'forms' => $forms,
            'settings' => $this->settings,
            'firm_id' => $this->settings->firm_id,
            'user' => $this->user,
        ]);
    }

    public function form_builder(Request $request, $id)
    {
        if($id == 'new'){
            $form = [];
        } else {
            $form = Form::where('uuid', $id)->first();
        }
        return view('dashboard.form_builder', [
            'form' => $form,
            'settings' => $this->settings,
            'firm_id' => $this->settings->firm_id,
            'user' => $this->user,
        ]);
    }

    public function post(Request $request)
    {
        $data = $request->all();
        if(isset($data['uuid'])) {
            $name = $data['form_name'];
            $form_data = $data['data'];
            Form::where(['uuid' => $data['uuid'],])->update(
             [
                'type' => 'user',
                'name' => $name,
                'data' => $form_data,
                'user_id' => \Auth::id(),
                'firm_id' => $this->settings->firm_id,
                'case_id' => 0,
            ]);
        } else {
            $uuid = Uuid::generate()->string;
            $name = $data['form_name'];
            $form_data = $data['data'];
            Form::create([
                'uuid' => $uuid,
                'type' => 'user',
                'name' => $name,
                'data' => $form_data,
                'user_id' => \Auth::id(),
                'firm_id' => $this->settings->firm_id,
                'case_id' => 0,
            ]);
        }

        return redirect()->back()->with('status', 'Form created and fields mapped');
    }

    public function view(Request $request, $id)
    {
        $form = Form::where('uuid', $id)->first();
        return view('dashboard.form_view', [
            'form' => $form,
            'settings' => $this->settings,
            'firm_id' => $this->settings->firm_id,
            'user' => $this->user,
        ]);
    }

    public function store_user_input(Request $request)
    {
        $data = $request->all();
        $uuid = Uuid::generate()->string;

        $fc = FormCompleted::create([
            'uuid' => $uuid,
            'form_uuid' => $data['form_uuid'],
            'user_data' => $data['user_data'],
        ]);

        return redirect()->back()->with('status', 'Form submitted!');
    }

    public function view_form_results(Request $request, $id)
    {
        $fc = Form::where('uuid', $id)->with('completed')->first();
        return view('dashboard.forms_completed', [
            'form' => $fc,
            'settings' => $this->settings,
            'firm_id' => $this->settings->firm_id,
            'user' => $this->user,
        ]);
    }

    public function view_single_form_results(Request $request, $form_id, $id)
    {
        $fc = FormCompleted::where('uuid', $id)->first();
        $data = $fc->user_data;
        return view('dashboard.forms_completed_results', [
            'form' => $fc,
            'data' => json_decode($data),
            'settings' => $this->settings,
            'firm_id' => $this->settings->firm_id,
            'user' => $this->user,
        ]);
    }
}
