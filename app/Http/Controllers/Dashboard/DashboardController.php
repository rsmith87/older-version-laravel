<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\User;
use App\Settings;
use App\Task;
use App\Document;
use App\WysiwygDocument;
use App\View;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
      if($this->user->created_at === $this->user->updated_at){
        //how to tell if new user, but doesn't discriminate between users for roles
      }    
      
      if(null !== Settings::where('user_id', \Auth::id())->first()){
        Settings::create([
          'user_id' => \Auth::id(),
          'theme' => 'flatly',
          'table_color' => 'light',
          'table_size' => 'lg',
        ]);
      }
     
     if(!isset($this->settings->firm_id)){
        return redirect('/dashboard/firm')->with('status', 'First, let\'s setup your firm.  Input the fields below to start.');
      }      
    

      
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
 
      
      return view('dashboard/dashboard', ['user_name' => $this->user['name'], 'theme' => $this->settings->theme, 'firm_id' => $this->settings->firm_id, 'user' => $this->user]);
    }
  
  
    public function create_roles_and_permissions()
    {
      //print_r($this->user->getRoleNames());
      //give master admin administrator role
     // User::find(9)->assignRole('client');
      return redirect('/dashboard')->with('status', 'Roles and permissions created!');
     
      
    }
  

}  
