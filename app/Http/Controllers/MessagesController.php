<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\LawCase;
use App\Contact;
use Carbon\Carbon;
use App\Settings;

use App\Message;
use App\Participant;
use App\Thread;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;


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
        if(!$this->user){
            return redirect('/login');
        }			
        if(!$this->user->hasPermissionTo('view messages')){
            return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
        }		            
        $this->settings = Settings::where('user_id', $this->user['id'])->first();
        $this->event_types = ['court', 'client meeting', 'blocker', 'lunch', 'meeting', 'research', 'booked'];
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
      $threads = \App\Thread::getAllLatest()->get();
      // All threads that user is participating in
      // $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();
      // All threads that user is participating in, with new messages
      // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

      $firm_users = Settings::where('firm_id', $this->settings->firm_id)->with('firm')->get();

      //print_r($firm_users);

      if(count($firm_users) > 0) {
        foreach($firm_users as $u){
          $usrs[] = User::where('id', $u->user_id)->with('settings')->get();
        }
      } else {
        $usrs = [];
      }
      

      //$users = User::where('id',  '!=', \Auth::id())->get();
      if($this->user->hasRole('client')){
        $contact = Contact::where('has_login', $this->user['id'])->first();
        $case = LawCase::where('id', $contact->case_id)->first();
        $users = User::where('id', $case->u_id)->first();
      }

      return view('messenger.index', [
        'user' => $this->user,

        //'inboxes' => $inboxes,
        'users' => $usrs,
        'theme' => $this->settings->theme,
        'firm_id' => $this->settings->firm_id,
        'settings' => $this->settings,
        'threads' => $threads,
        'firm_users' => $usrs,
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
        'user' => $this->user,
        'users' => $usrs, 
        'thread' => $thread, 
        'threads' => $threads,
        'message' => $message,
        'theme' => $this->settings->theme,
        'firm_id' => $this->settings->firm_id,
        'settings' => $this->settings,
        'firm_users' => $firm_users,
      ]);
    }
	
    public function show_ajax($id, Request $request)
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
      //$users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
      //$participants = Participant::where('thread_id', $thread->id)->get();
      //foreach($participants as $participant){
          //$par[$participant->user_id] = User::where('id', $participant->user_id)->get();
      //}
      //$thread->markAsRead($userId);
      //$message = Message::where('thread_id', $thread->id)->get();

      return view('messenger.show');
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', [
          'users' => $users,  
          'theme' => $this->settings->theme
        ]);
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
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
        $message_uuid = Uuid::generate()->string;

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
            Session::flash('errors', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('messages');
        }
        $thread->activateAllParticipants();
        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Input::get('message'),
        ]);
        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant(Input::get('recipients'));
        }
        return redirect()->route('messages.show', $id);
    }

		public function ajaxSendMessage(Request $request)
		{
			if ($request->ajax()) {
				$rules = [
					'message-data'=>'required',
					'_id'=>'required'
				];
				$this->validate($request, $rules);
				$body = $request->input('message-data');
				$userId = $request->input('_id');
				if ($message = Talk::sendMessageByUserId($userId, $body)) {
					$html = view('ajax.newMessageHtml', compact('message'))->render();
					return response()->json(['status'=>'success', 'html'=>$html], 200);
				}
			}
		}
		public function ajaxDeleteMessage(Request $request, $id)
		{
			if ($request->ajax()) {
				if(Talk::deleteMessage($id)) {
					return response()->json(['status'=>'success'], 200);
				}
				return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
			}
		}
}