<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use App\LawCase;
use App\Contact;
use Carbon\Carbon;
use App\Settings;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class MessagesController extends Controller
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
						if(!$this->user->hasPermissionTo('view messages')){
							return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
						}	
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
    }
    /**
     * Show all of the message threads to the user.
     *
     * @return index
     */
    public function index(Request $request, $id = NULL)
    {
        // All threads, ignore deleted/archived participants

        //$threads = Thread::getAllLatest()->get();


        //$threads = Thread::getAllLatest()->get();

      
        // All threads that user is participating in
        $threads = Thread::forUser($this->user['id'])->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        //$threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
			
			
        $users = User::where('id',  '!=', $this->user['id'])->get();
				if($this->user->hasRole('client')){
							$contact = Contact::where('has_login', $this->user['id'])->first();
		
									$case = LawCase::where('id', $contact->case_id)->first();

        	$users = User::where('id', $case->u_id)->get();
			 	
				
				}
        return view('messenger.index', [
					'threads' => $threads, 
					'users' => $users, 
					'theme' => $this->settings->theme,
					'firm_id' => $this->settings->firm_id,
				]);
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id, Request $request)
    {
      
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return view('messenger.show', [
					'users' => $users, 
					'thread' => $thread, 
					'theme' => $this->settings->theme,
					'firm_id' => $this->settings->firm_id,
				]);
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', ['users' => $users,  'theme' => $this->settings->theme]);
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input = Input::all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        return redirect()->route('messages');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        $thread->activateAllParticipants();
				
        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $this->user['id'],
            'body' => Input::get('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => $this->user['id'],
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant(Input::get('recipients'));
        }

        return redirect()->route('messages.show', $id);
    }
}