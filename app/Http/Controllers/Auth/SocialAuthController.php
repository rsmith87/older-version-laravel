<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Socialite;
use App\User;
use App\Settings;
use App\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Uuids;
use Webpatser\Uuid\Uuid;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Http\Redirect;

class SocialAuthController extends Controller
{
  
  use RegistersUsers;
  use VerifiesUsers;
  
    protected $redirectPath = '/login';
    
    public function __construct()
    {
      $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
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

      if($authUser->verified != 1){
       Settings::create([
        'user_id' => $authUser->id,
        'theme' => 'flatly',
        'table_color' => 'light',
        'table_size' => 'lg',
      ]);
      View::create([
        'view_type' => 'contact',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $authUser->id,
      ]);  

      View::create([
        'view_type' => 'case',
        'view_data' => json_encode(['case_uuid', 'name', 'court_name'], true),
        'u_id' => $authUser->id,
      ]); 
      View::create([
        'view_type' => 'client',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $authUser->id,
      ]);        
      
   
      event(new Registered($authUser));
      //send(AuthenticatableContract $user, $subject, $from = null, $name = null)
      UserVerification::generate($authUser);
      UserVerification::send($authUser, 'Legalkeeper User Verification');
        return redirect($this->redirectPath)->with('status', 'Check your email to verify your account!');
      
      }
      else {
        Auth::login($authUser, true);
        return redirect('/dashboard')->with('status', 'Successfully logged in with facebook!');
    
    }
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
        $uuid = Uuid::generate()->string;

        $user = User::create([
            'id' => $uuid,
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
        ]);
      $user->assignRole('authenticated_user');
      return $user;

    }
}
