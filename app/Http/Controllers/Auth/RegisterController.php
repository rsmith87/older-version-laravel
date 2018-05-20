<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Settings;
use App\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use VerifiesUsers;
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
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
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function register(\Illuminate\Http\Request $request)
    {
      
      $data = $request->all();
   
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
      
      $inserted = User::where('email', $data['email'])->first();
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
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $inserted->id,
      ]);  

      View::create([
        'view_type' => 'case',
        'view_data' => json_encode(['case_uuid', 'name', 'court_name'], true),
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
