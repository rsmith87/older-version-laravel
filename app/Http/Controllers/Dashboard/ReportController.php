<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;

class ReportController extends Controller
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
			if(!$this->user->hasPermissionTo('view contacts')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}					
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			return $next($request);
		});
	}
  
  public function index()
  {
    return view('dashboard/reports', ['user_name' => $this->user['name'], 'firm_id' => $this->settings->firm_id, 'theme' => $this->settings->theme]);
  }
}
