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

class FirmStripeController extends Controller
{
  
  use VerifiesUsers;

  public function add_stripe_payment(Request $request)
  {

		  $user = \Auth::id();


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
	      //THIS CURRENTLY GETS THE ACTIVE LOGGED IN USER
	      //NEED TO MAKE IT REFERENECE THE NEW USER CREATED
	      $user = User::find($user);

        if(isset($input['cc_coupon_code']) && $input['cc_coupon_code'] != ""){
	        $charge = $user->newSubscription('main', 'plan_DH9vLJvUYAeco7')->withCoupon($input['cc_coupon_code'])->create($token['id']);
        } else {
	        $charge = $user->newSubscription('main', 'plan_DH9vLJvUYAeco7')->create($token['id']);
        }

	      if ($user->subscribed('main')) {


	        return redirect('/dashboard/firm')->with('status', 'Firm member paid for and can be used for login!  An email to reset their password has been sent to the new users email.');

          
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
