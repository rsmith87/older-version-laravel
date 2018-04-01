<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Settings;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Role;

class MessagesController extends Controller
{
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
      if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
        $this->middleware('auth');
        $this->user = \Auth::user();
        $this->settings = Settings::where('user_id', \Auth::id())->first();
        $this->role = $request->user()->hasRole('auth_user');
        //->s3 = \Storage::disk('s3');
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
        $not_allowed = $request->user()->hasRole('administrator');      


        //$threads = Thread::getAllLatest()->get();

      
        // All threads that user is participating in
        $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        //$threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.index', ['threads' => $threads, 'users' => $users, 'theme' => $this->settings->theme, 'role' => $not_allowed]);
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id, Request $request)
    {
        $not_allowed = $request->user()->hasRole('administrator');
      
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

        return view('messenger.show', ['users' => $users, 'thread' => $thread, 'theme' => $this->settings->theme, 'role' => $not_allowed]);
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', ['users' => $users,  'theme' => $this->settings->theme, 'role' => $this->role]);
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
}