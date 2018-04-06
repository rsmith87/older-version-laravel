<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Notifications;
use App\Firm;
use App\Http\Requests;
use App\User;
use Mail;
use Password;
use App\Settings;
use App\Contact;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\ResetEmailNotification;

class FirmController extends Controller
{
      use ResetsPasswords;

    protected $subject = "Your Account Password";


	    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = \Auth::user();
					  $this->passwords = Password::broker();
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
    }
	
  public function index(Request $request)
    {
    	if(!isset($this->settings)){
				$clients = 0;
				$firm = 0;
				$firm_staff = 0;
				Settings::create([
				  'user_id' => \Auth::id(),
					'theme' => 'flatly',
					'table_color' => 'light',
					'table_size' => 'lg',
				]);
			}
		  else {
			  $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
      	$firm = Firm::where('id', $this->settings->firm_id)->first();
       	$firm_staff = User::where('f_id', $this->settings->firm_id)->select()->get();
			}
			
    	$this->settings = Settings::where('user_id', \Auth::id())->first();
      return view('dashboard/firm', [
        'user_name' => $this->user['name'],  
        'theme' => $this->settings->theme,
        'firm' => $firm,
        'f_id' => $this->settings->firm_id,
				'firm_id' => $this->settings->firm_id,
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
        'name' => $data['name'], 
        'address_1' => $data['address_1'],
				'address_2' => $data['address_2'],
				'city' => $data['city'],
				'state' => $data['state'],
				'zip' => $data['zip'],
        'phone' => $data['phone'],
        'fax' => $data['fax'],
        'email' => $data['email'],
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
		}	else {
		}
		

		
		
		
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
		
		//foreach($u as $t){
			//$this->sendPasswordResetNotification($request);
		//};

		return redirect('/dashboard/settings')->with('status', 'Client {{ $name }} added and reset password email sent!');

	}

	public static function generatePassword()
	{
		// Generate random string and encrypt it. 
		return bcrypt(str_random(35));
	}
  
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

  
}
