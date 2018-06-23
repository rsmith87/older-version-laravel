<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaShareController extends Controller
{
  public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$this->user = \Auth::user();
      if(!$this->user){
				return redirect('/login');
			}			
			if(!$this->user->hasPermissionTo('view documents')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}						
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
      
			$this->s3 = \Storage::disk('s3');
			return $next($request);
		});
	}
  
  public function share()
  {
    
  }
}