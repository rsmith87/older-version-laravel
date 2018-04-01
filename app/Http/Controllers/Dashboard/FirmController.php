<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Firm;
use App\Http\Requests;
use App\User;
use App\Role;
use Mail;
use Password;
use App\Settings;
use App\Contact;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Controller;

class FirmController extends Controller
{
      use ResetsPasswords;

    protected $subject = "Your Account Password";

  public function __construct(Guard $auth, PasswordBroker $passwords)
  {
      $this->middleware('auth');
      $this->user = \Auth::user();
      $this->auth = $auth;
      $this->passwords = $passwords;
      $this->settings = Settings::where('user_id', \Auth::id())->first();    
  }  
  public function index(Request $request)
    {
    
      $not_allowed =  $request->user()->authorizeRoles(['administrator']);

			
				
			//print_r($request->user()->roles());
      $settings = User::where('id', \Auth::id())->with('settings')->first();
      //print_r($settings);
   
       $theme = $this->settings->theme;
     
      $firm_id = $this->settings->firm_id;
		  $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
			
      //$firm_id = Settings::where('user_id', \Auth::id())->first();

      $firm = Firm::where('id', $firm_id)->first();
      $firm_staff = User::where('f_id', $firm_id)->select()->get();
    
      
      if ($firm)
      {
        $f_name = $firm->firm_name;
        $f_address = $firm->firm_address;
        $f_phone = $firm->firm_phone;
        $f_fax = $firm->firm_fax;
        $f_email = $firm->firm_email;
        
      }
      else
      {
        $firm = "";
        $f_name = "";
        $f_address = "";
        $f_phone = "";
        $f_fax = "";
        $f_email = "";
        
      }
    
    //print_r($this->user);
      return view('dashboard/firm', [
        'user_name' => $this->user['name'],  
        'theme' => $theme,
        'firm' => $firm,
        'f_name' => $f_name,
        'f_address' => $f_address,
        'f_phone' => $f_phone,
        'f_fax' => $f_fax,
        'f_email' => $f_email,
        'f_id' => $firm_id,
        'role' => $not_allowed,
        'firm_staff' => $firm_staff,
				'clients' => !empty($clients) ? $clients : null,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
      ]);
  
  }
  
  public function add(Request $request)
  {
      $data = $request->all();
      
      if(empty($this->settings->firm_id)){
        $id = \DB::table('firm')->max('id') + 1;
        //$settings = $this->settings;
        $this->settings->firm_id = $id;
        $this->settings->save();         
      }
      else
      {
        $id = $this->settings->firm_id;       
      }
 
      Firm::updateOrCreate(
      [
        'id' => $id, 
      ],
      [
        'firm_name' => $data['firm_name'], 
        'firm_address' => $data['firm_address'],
        'firm_phone' => $data['firm_phone'],
        'firm_fax' => $data['firm_fax'],
        'firm_email' => $data['firm_email'],
      ]);
      
      return redirect('/dashboard/firm')->with('status', 'Firm updated!');
      
      
  }

  public function add_user(Request $request)
  {
      $data = $request->all();

    
    if(!isset($data['existing_name'])){

   //generate a password for the new users
    $pw = $this->generatePassword();
    //add new user to database
    $u = new User;
    $u->name = $data['name'];
    $u->email = $data['email'];
    $u->f_id = $this->settings->firm_id;
    $u->password = $pw;
    $u->save();    
    
    $id = User::where('email', $data['email'])->first();
    
    $s = new Settings;
    $s->firm_id = $this->settings->firm_id;
    $s->theme = $this->settings->theme;
    $s->user_id = $id->id;
    $s->save();
			
		if(isset($data['client'])){
    $u = $u->roles()->attach(Role::where('name', 'client')->first());
		}	else {
    $u = $u->roles()->attach(Role::where('name', 'auth_user')->first());			
		}

		$this->sendResetLinkEmail($request);
    $status = 'User created and an email was sent to user\'s email address provided.';
    }
    else {   
      $update_user = User::where('email', $data['existing_email'])->first();
      
      $update_user->name = $data['existing_name'];
      $update_user->email = $data['existing_email'];
      $update_user->save();
      $status = "User updated!";
    }
    
    return redirect('/dashboard/firm')->with('status', $status);

     
  }
	
	public function create_client_login(Request $request)
	{
		$data = $request->all();
		$name = $data['client_name'];
		$email = $data['email'];
		//generate a password for the new users
		$pw = $this->generatePassword();
    $u = new User;
    $u->name = $data['client_name'];
    $u->email = $data['email'];
    $u->f_id = $this->settings->firm_id;
    $u->password = $pw;
    $u->save();    
		
    $id = User::where('email', $data['email'])->first();
    
    $s = new Settings;
    $s->firm_id = $this->settings->firm_id;
    $s->theme = $this->settings->theme;
    $s->user_id = $id->id;
    $s->save();
    $u = $u->roles()->attach(Role::where('name', 'client')->first());		
		
		$this->sendResetLinkEmail($request);

		return redirect('/dashboard/settings')->with('status', 'Client {{ $name }} added and reset password email sent!');

	}

	public static function generatePassword()
	{
		// Generate random string and encrypt it. 
		return bcrypt(str_random(35));
	}
  


  
}
