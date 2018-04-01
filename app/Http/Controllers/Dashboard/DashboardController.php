<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\User;
use App\Settings;
use App\Task;
use App\Role;
use App\Document;
use App\WysiwygDocument;
use App\View;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
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
           // print_r();
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
        //$this->user = \Auth::user();
    }
  
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if(null === $this->user->roles()){
        \DB::table('role_users')->insert(['role_id' => 1, 'user_id' => \Auth::id()]);
      }    
      if(null === Settings::where('user_id', \Auth::id())->first()){
        Settings::create([
          'user_id' => \Auth::id(),
          'theme' => 'flatly',
          'table_color' => 'light',
          'table_size' => 'lg',
        ]);
      }
      //print_r($this->settings);
     if(!$this->settings->firm_id){
        return redirect('/dashboard/firm')->with('status', 'First, let\'s setup your firm.  Input the fields below to start.');
      }      
      $request->user()->authorizeRoles(['auth_user', 'administrator', 'client']);
      $not_allowed = $request->user()->hasRole('administrator');      

      
      if(!View::where('u_id', \Auth::id())->first()){
          View::create([
        'view_type' => 'contact',
        'view_data' => json_encode(array('id', 'first_name', 'last_name', 'phone'), true),
        'u_id' => \Auth::id(),
      ]);  
      
      View::create([
        'view_type' => 'case',
        'view_data' => json_encode(array('id', 'name', 'court_name'), true),
        'u_id' => \Auth::id(),
      ]); 
      View::create([
        'view_type' => 'client',
        'view_data' => json_encode(array('id', 'first_name', 'last_name', 'phone'), true),
        'u_id' => \Auth::id(),
      ]);        
      }
 
      
      return view('dashboard/dashboard', ['user_name' => $this->user['name'], 'theme' => $this->settings->theme, 'user' => $this->user, 'role' => $not_allowed]);
    }
  
  
    
  
    public function reports(Request $request)
    {
      $request->user()->authorizeRoles(['auth_user', 'administrator']);
      $not_allowed = $request->user()->hasRole('administrator');  
      return view('dashboard/reports', ['user_name' => $this->user['name'], 'theme' => $this->settings->theme, 'role' => $not_allowed]);
    }
  

}  
