<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Error\Card;
use Illuminate\Validation\Validator;
use Cartalyst\Stripe\Stripe;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use App\User;

class StripeController extends Controller
{
  
  use VerifiesUsers;

  public function add_stripe_payment(Request $request)
  {
    
    $user = $request->session()->get('u_i');
    $validator =  $request->validate([
      'card_no' => 'required',
      'ccExpiryMonth' => 'required',
      'ccExpiryYear' => 'required',
      'cvvNumber' => 'required',
      'amount' => 'required',
    ]);
    
    $input = $request->all();
    
      $input = array_except($input,array('_token'));
      $stripe = Stripe::make(env('STRIPE_SECRET'));
      
      try {
        $token = $stripe->tokens()->create([
          'card' => [
          'number' => $request->get('card_no'),
          'exp_month' => $request->get('ccExpiryMonth'),
          'exp_year' => $request->get('ccExpiryYear'),
          'cvc' => $request->get('cvvNumber'),
          ],
        ]);

        if (!isset($token['id'])) {
          return redirect()->route('addmoney.paywithstripe');
        }
	      //need to have way to get user - needs to be through
	      $user = User::find($user);

        if(isset($input['cc_coupon_code'])){
	        $charge = $user->newSubscription('main', 'plan_DH9vLJvUYAeco7')->withCoupon($input['cc_coupon_code'])->create($token['id']);
        } else {
	        $charge = $user->newSubscription('main', 'plan_DH9vLJvUYAeco7')->create($token['id']);
        }

	      if ($user->subscribed('main')) {
          /**
          * Write Here Your Database insert logic.
          */
          //if the user is already verified then we send them to login page
		      if($user->isVerified()){
		      	return redirect('/login')->with('status', 'Payment updated.  Please login to continue.');
		      }

		      //if the user is not verified then we send them the verification email to make sure they're good
	        event(new Registered($user));
	        UserVerification::generate($user);
	        UserVerification::send($user, 'Legalkeeper User Verification');

	        return redirect('/login')->with('status', 'Check your email for a link to verify your account!');

          
        } else {
          \Session::put('error','Money not added in wallet!!');
          return redirect()->route('addmoney.paywithstripe');
        }

      } catch (Exception $e) {
        
        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');
        
      } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
        
        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');
        
      } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {

        \Session::put('error',$e->getMessage());
        return redirect()->route('addmoney.paywithstripe');

      }

  }
  
  
  public function add_payment(Request $request)
  {
    return view('vendor/adminlte/payment');
  }

}
