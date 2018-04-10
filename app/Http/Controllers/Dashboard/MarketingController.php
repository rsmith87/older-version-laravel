<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Settings;
use App\User;

use App\Http\Controllers\Controller;

class MarketingController extends Controller
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
		if(!$this->user->hasPermissionTo('view marketing')){
			return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
		}		
		$this->settings = Settings::where('user_id', $this->user['id'])->first();
		return $next($request);
		});
	}

	public function index(Request $request)
	{
		return view('dashboard/marketing', [
			'user_name' => $this->user['name'],  
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
		]);

	}

}
