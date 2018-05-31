<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Settings;
use App\FirmStripe;
use App\Thread;
use App\Http\Controllers\Controller;

class MailController extends Controller
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
        $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
        $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
        
        return $next($request);
      });
    }
    
    public function index()
    {
      return view('messenger.mail', [
         'user' => $this->user,
         'firm_id' => $this->settings->firm_id,
         'fs' => $this->firm_stripe,
         'settings' => $this->settings,
         'threads' => $this->threads
      ]);
    }
}
