<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Socialite;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins, RegistersUsers, AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

  
    /**
     * Where to redirect users after a logout
     *
     * @var string
     */
    protected $redirectAfterLogout = '/login';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
          
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
      //$user_id =  \DB::table('users')->max('id') + 1;    
        

      
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
     
 
      return $user;
      }
  
    public function logout()
    {
      
    }
  
    
     /* Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

     /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }
    
    
    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
       $inserted = User::where('email', $user->email)->first();
      $inserted->assignRole('authenticated_user');

      Settings::create([
        'user_id' => $inserted->id,
        'theme' => 'flatly',
        'table_color' => 'light',
        'table_size' => 'lg',
        'tz' => $data['timezone_register'],
      ]);
      View::create([
        'view_type' => 'contact',
        'view_data' => json_encode(['id', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $inserted->id,
      ]);  

      View::create([
        'view_type' => 'case',
        'view_data' => json_encode(['id', 'name', 'court_name'], true),
        'u_id' => $inserted->id,
      ]); 
      View::create([
        'view_type' => 'client',
        'view_data' => json_encode(['id', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $inserted->id,
      ]);        
      event(new Registered($inserted));
      //send(AuthenticatableContract $user, $subject, $from = null, $name = null)
      UserVerification::generate($user);
      UserVerification::send($user, 'Legalkeeper User Verification');

      return $this->registered($request, $user)
                        ?: redirect($this->redirectPath())->with('status', 'Check your email for a link to verify your account!');
    }
        
}
