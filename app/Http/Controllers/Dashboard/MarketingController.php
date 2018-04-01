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
            $this->user_id = $this->user['id'];
            $this->settings = Settings::where('user_id', $this->user_id)->first();
            return $next($request);
        });
    }
  
public function index(Request $request)
    {
    
      $not_allowed =  $request->user()->authorizeRoles(['administrator']);

			
				
			//print_r($request->user()->roles());
      $settings = User::where('id', \Auth::id())->with('settings')->first();
      //print_r($settings);
   
      $theme = $this->settings->theme;
     
      $firm_id = $this->settings->firm_id;
		  
    
    //print_r($this->user);
      return view('dashboard/marketing', [
        'user_name' => $this->user['name'],  
        'theme' => $theme,
        'role' => $not_allowed,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
      ]);
  
  }
    
}
