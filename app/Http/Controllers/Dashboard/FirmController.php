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
						if(!$this->user){
							return redirect('/login');
						}					
						if(!$this->user->hasPermissionTo('view firm')){
							return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
						}					
					  $this->passwords = Password::broker();
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
			
            return $next($request);
        });
    }
	
  public function index(Request $request)
    {
			
    	if(!isset($this->settings)){
				Settings::create([
				  'user_id' => $this->user['id'],
					'theme' => 'flatly',
					'table_color' => 'light',
					'table_size' => 'lg',
				]);
				$firm_staff = [];
				$clients = [];
			}
		  else {
				$firm_staff = [];
				$names = [];
			  $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => 1])->get();
				$c = json_decode($clients, true);
      	$firm = Firm::where('id', $this->settings->firm_id)->first();
				$firm_users = Settings::where('firm_id', $this->settings->firm_id)->select('user_id')->get();
				
				//prepping data for client/user compare to list clients in client area and users in user area on firm page
				foreach($c as $test){
					$names[] = $test['first_name'] . " " . $test['last_name'];
				}
				
				//loop through each user id that came from settings
				foreach($firm_users as $user){
						$users = json_decode(User::where('id', $user->user_id)->get(), true);
					
					//loop through each user data info
					foreach($users as $u){
						
						//if the user array name has the same name from the clients above
						//then dont add it to array firm_staff
						if(!in_array($u['name'], $names)){
							$firm_staff[] = $u;
						}
						
					}
				}
			}
			

    	$this->settings = Settings::where('user_id', $this->user['id'])->first();
      return view('dashboard/firm', [
        'user_name' => $this->user['name'],  
        'theme' => $this->settings->theme,
        'firm' => $firm,
        'f_id' => $this->settings->firm_id,
				'firm_id' => $this->settings->firm_id,
        'firm_staff' => $firm_staff,
				'clients' => $clients,
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
    $u->password = $pw;
			
    $u->save();    
    
    $id = User::where('email', $data['email'])->first();
    $id->assignRole('authenticated_user');			
    
    $s = new Settings;
    $s->firm_id = $this->settings->firm_id;
    $s->theme = $this->settings->theme;
    $s->user_id = $id->id;
    $s->save();
					
		
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
		$contact = Contact::where('id', $data['client_id'])->first();
				
		User::updateOrCreate([
			'name' => $contact->first_name . " " . $contact->last_name,
		],[
			'email' => $contact->email,
			'password' => $this->generatePassword(),
			
		]); 
		
    $id = User::where('email', $contact->email)->first();
		$contact->update(['has_login'=>$id->id]);
    $id->assignRole('client');
		
		Settings::updateOrCreate([
			'user_id' => $id->id,
		],[
			'theme' => $this->settings->theme,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
			'firm_id' => $this->settings->firm_id,
		]);
		
	
		$email_send = $id->sendPasswordResetNotification($id);
		

		return redirect('/dashboard/firm')->with('status', 'Client '.$id->name.' added and reset password email sent!');

	}

	public static function generatePassword()
	{
		// Generate random string and encrypt it. 
		return bcrypt(str_random(35));
	}
  
  
}
