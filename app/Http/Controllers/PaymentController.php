<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\LawCase;
use App\Contact;
use App\Firm;
use App\FirmStripe;

class PaymentController extends Controller
{
    public function __construct()
    {

    }

    public function get_payment_details($firm_id, $invoice_id)
    {

			$invoice = Invoice::where('invoice_uuid', $invoice_id)->first();
			$case = LawCase::where('case_uuid', $invoice->invoicable_id)->first();
			$client = Contact::where(['case_id' => $case->id, 'is_client' => 1])->first();
			$firm = Firm::where('id', $firm_id)->first();

			return view('dashboard.client_payment', [
				'invoice' => $invoice,
				'client' => $client,
				'firm' => $firm,
			]);
    }

    public function get_invoice($firm_id, $invoice_id)
    {
        $invoice = Invoice::where('invoice_uuid', $invoice_id)->with('invoicelines')->first();
        $case = LawCase::where('case_uuid', $invoice->invoicable_id)->with('contacts')->first();
        $firm = Firm::where('id', $firm_id)->first();
        $client = Contact::where(['case_id' => $case->id, 'is_client' => '1'])->first();

        return view('common.invoice', [
            'invoice' => $invoice,
            'firm' => $firm,
            'client' => $client,
            'case' => $case,
        ]);
    }

    public function post_payment_details(Request $request, $firm_id, $invoice_id)
    {
        //get firm stripe id to make payment against connected account
        //post to stripe with invoice info and firm stripe id
        //return back a page that says your payment is successful and has been marked as paid
        $data = $request->all();

        //need to figure out how to charge without a user account because laravel does not like that
        //use stripe library alone to do this not laravels integrated stripe library
        $invoice = Invoice::where('invoice_uuid', $invoice_id)->first();
        $case = LawCase::where('case_uuid', $invoice->invoicable_id)->first();
        $client = Contact::where(['case_id' => $case->id, 'is_client' => 1])->first();
        $firm = Firm::where('id', $firm_id)->first();
        $fs = FirmStripe::where('firm_id', $firm->id)->first();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $invoice->total*100,
                "currency" => "usd",
                "source" => $data['stripeToken'],
                'statement_descriptor' =>  substr($invoice->sender_info, 0, 22),
            ), array("stripe_account" => $fs->stripe_account_id));

            Invoice::where('invoice_uuid', $invoice_id)->update([
                'paid' => 1,
            ]);
            return redirect('/payment/'. $invoice_id .'/complete')->with('status', 'Thank you for your payment!  Your payment has been submitted successfully.  You can now close this window.');

        } catch (Exception $e) {
            //charge failed
            Invoice::where('invoice_uuid', $invoice_id)->update([
                'paid' => 0,
            ]);
            return redirect()->back()->with('status', 'Your payment was not completed.  Please try again');
        }

    }

    public function payment_complete(Request $request, $id)
    {
        $invoice = Invoice::where('invoice_uuid', $id)->first();
        $case = LawCase::where('case_uuid', $invoice->invoicable_id)->first();
        $client = Contact::where(['is_client' => 1, 'case_id' => $case->id])->first();
        $firm = Firm::where('id', $case->firm_id)->first();

        return view('common.payment-complete', [
           'invoice' => $invoice,
           'firm' => $firm,
           'client' => $client,
        ]);
    }
}
