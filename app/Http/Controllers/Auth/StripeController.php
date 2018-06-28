<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;

class StripeController extends Controller
{
  
  use VerifiesUsers;

  public function add_stripe_payment(Request $request)
  {
    
    $request->session()->get('u_e');
    $validator = Validator::make($request->all(), [
      'card_no' => 'required',
      'ccExpiryMonth' => 'required',
      'ccExpiryYear' => 'required',
      'cvvNumber' => 'required',
      'amount' => 'required',
    ]);
    
    $input = $request->all();
    
    if ($validator->passes()) { 
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
        $charge = $stripe->charges()->create([
          'card' => $token['id'],
          'currency' => 'USD',
          'amount' => 10.49,
          'description' => 'Add in wallet',
        ]);

        if($charge['status'] == 'succeeded') {
          /**
          * Write Here Your Database insert logic.
          */
          //it has succeeded
          
          $this->handle_payment();
         
          
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
  }
  
  
  public function add_payment(Request $request)
  {
    return view('vendor/adminlte/payment');
  }
  
  
  private function handle_payment()
  {
    $user = User::find(1);

    $user->newSubscription('main', 'premium')->create($stripeToken);
    event(new Registered($inserted));
    UserVerification::generate($user);
    UserVerification::send($user, 'Legalkeeper User Verification');
    
    return redirect('/login')->with('status', 'Check your email for a link to verify your account!');
  }
}
