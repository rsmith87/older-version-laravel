<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Settings;
use App\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Jrean\UserVerification\Facades\UserVerification as UserVerificationFacade;
use Jrean\UserVerification\Exceptions\UserNotFoundException;
use Jrean\UserVerification\Exceptions\UserIsVerifiedException;
use Jrean\UserVerification\Exceptions\TokenMismatchException;

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

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    
    protected $redirectPath = '/register/payment';
    protected $redirectTo = '/register/payment';    

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
      $inserted->assignRole('firm_manager');

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
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' => $inserted->id,
      ]);        

      session(['u_i' => $inserted->id]);
      return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

	/**
	 * Handle the user verification.
	 *
	 * @param  string  $token
	 * @return \Illuminate\Http\Response
	 */
	public function getVerification(Request $request, $token)
	{
		if (! $this->validateRequest($request)) {
			return redirect($this->redirectIfVerificationFails());
		}

		try {
			$user = UserVerificationFacade::process($request->input('email'), $token, $this->userTable());
		} catch (UserNotFoundException $e) {
			return redirect($this->redirectIfVerificationFails());
		} catch (UserIsVerifiedException $e) {
			return redirect($this->redirectIfVerified());
		} catch (TokenMismatchException $e) {
			return redirect($this->redirectIfVerificationFails());
		}

		if (config('user-verification.auto-login') === true) {
			auth()->loginUsingId($user->id);
		}

		return redirect($this->redirectAfterVerification());
	}

	protected function validateRequest(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email'
		]);

		return $validator->passes();
	}

	/**
	 * Get the user table name.
	 *
	 * @return string
	 */
	protected function userTable()
	{
		return property_exists($this, 'userTable') ? $this->userTable : 'users';
	}

	/**
	 * Get the redirect path if the user is already verified.
	 *
	 * @return string
	 */
	public function redirectIfVerified()
	{
		return property_exists($this, 'redirectIfVerified') ? $this->redirectIfVerified : '/';
	}

	/**
	 * Get the redirect path for a successful verification token verification.
	 *
	 * @return string
	 */
	public function redirectAfterVerification()
	{
		return property_exists($this, 'redirectAfterVerification') ? $this->redirectAfterVerification : '/';
	}

	/**
	 * Get the redirect path for a failing token verification.
	 *
	 * @return string
	 */
	public function redirectIfVerificationFails()
	{
		return property_exists($this, 'redirectIfVerificationFails') ? $this->redirectIfVerificationFails : route('email-verification.error');
	}
}
