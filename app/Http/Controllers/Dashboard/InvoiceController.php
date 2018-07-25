<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Settings;
use App\User;
use App\Firm;
use App\Order;
use App\LawCase;
use App\Contact;
use App\Invoice;
use App\InvoiceLine;
use App\CaseHours;
use App\FirmStripe;
use App\Thread;
use App\Notifications\InvoiceCreatedNotification;
use Faker\Factory;
use Carbon;
use SanderVanHooft\Invoicable\MoneyFormatter;
use Webpatser\Uuid\Uuid;


use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
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
		if(!$this->user->hasPermissionTo('view invoices')){
			return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
		}		
		$this->settings = Settings::where('user_id', $this->user['id'])->first();
    $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
    $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
    
			return $next($request);
		});
	}

  
	public function index(Request $request)
	{
		if(!$this->user->hasRole('client')){
			$invoices= Invoice::where('user_id', $this->user['id'])->get();
		} 
		else {
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$orders = Order::where('client_id', $contact->id)->get();
		}
		return view('dashboard/invoices', [
			'user' => $this->user,  
			'theme' => $this->settings->theme,
			'invoices' => $invoices,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,
      'threads' => $this->threads
		]);
	}

  
	public function create(Request $request)
	{		

		$data = $request->all();

		//find id to use
		if(array_key_exists('id', $data)){
			$id = $data['order_id'];
			$invoice_id = $data['invoice_id'];
			$invoice_line_id = $data['invoice_line_id'];
		}
		else {
			$id = \DB::table('orders')->max('id') + 1;
			$invoice_id = \DB::table('invoices')->max('id') + 1;
			$invoice_line_id = \DB::table('invoice_lines')->max('id') + 1;
		}

		//getting data to populate the DB
		$case = LawCase::where('case_uuid', $data['case_uuid'])->first();
    $case_hours = CaseHours::where('case_uuid', $data['case_uuid'])->get();
    $hours_amount = '0';
    foreach($case_hours as $ch){
      $hours_amount += $ch->hours;
    }
		$firm = Firm::where('id', $this->settings->firm_id)->first();
		$client = Contact::where('id', $data['client_id'])->first();
		$amount = $data['amount'];
		$firm_address = $firm->address_1 . " " . $firm->address_2 . " " . $firm->city . " " . $firm->state . " " . $firm->zip;
		
		$bill_type = $case->billing_type;
		if($bill_type === 'hourly'){
			$total_amount  = $hours_amount * $case->billing_rate;
		}
		else {
			$total_amount = $case->billing_rate;
		}
  
    
		$amount_remaining = $total_amount - $data['amount'];
    
    $mycase = Order::where(['user_id' => $this->user['id'], 'case_uuid' => $case->case_uuid])->first();

		if(count($mycase) > 0){
			$orig_amount = $mycase->amount + floatval($data['amount']);
      LawCase::where('id', $case->id)->update(['order_id' => $mycase->id]);
      
		} else {
      $orig_amount = 0;
    }
    
    
    $email_send = $client->sendTaskDueReminder($client);

    if(isset($data['order_uuid'])){
      $order_uuid = $data['order_uuid'];
    } else {
      $order_uuid = Uuid::generate()->string;
    }
    
    if(isset($data['invoice_uuid'])){
      $invoice_uuid = $data['invoice_uuid'];
    } else {
        $invoice_uuid = Uuid::generate()->string;
    }              
		Order::updateOrCreate([
      'order_uuid' => $order_uuid,
		],
		[
      'case_uuid' => $data['case_uuid'],
			'amount' => $orig_amount,
			'amount_remaining' => floatval($amount_remaining),
			'firm_id' => $this->settings->firm_id,
			'client_id' => $data['client_id'],
			'user_id' => $this->user['id'],
		]);


		Invoice::updateOrCreate([
			'id' => $invoice_id,
		],
		[
      'invoice_uuid' => $invoice_uuid,
			'invoicable_id' => $data['case_uuid'],
			'invoicable_type' => 'app_client',
			'description' => $data['invoice_description'],
			'tax' => 0,
			'total' => $data['amount'],
			'currency' => 'USD',
			'status' => 'invoiced',
			'receiver_info' => $client->first_name . ' ' . $client->last_name,
			'sender_info' => 'Re: '. $case->name,
			'payment_info' => 'stripe',
			'note' => '',
			'user_id' => $this->user['id'],
		]);

		InvoiceLine::updateOrCreate([
			'id' => $invoice_line_id,
		],[
			'invoice_id' => $invoice_id,
			'amount' => $data['amount'],
			'tax' => 0,
			'tax_percentage' => 0,
			'description' => $client->id . ": " .$client->first_name . " " . $client->last_name,
		]);

		return redirect('/dashboard/invoices')->with('message', 'Your invoice is being generated and a link to the invoice will show when it is ready.');
	}

	public function invoice_view($id)
	{
		$invoice = Invoice::where('invoice_uuid', $id)->with('invoicelines')->first();
		$case = LawCase::where('case_uuid', $invoice->invoicable_id)->with('contacts')->first();
		$firm = Firm::where('id', $this->settings->firm_id)->first();
		foreach($case->Contacts as $contact){
			if($contact->is_client === 1){
				$client = $contact;
			} 
		}

		return view('dashboard.invoice', [
      'user' => $this->user,
			'invoice' => $invoice,
			'firm' => $firm,
			'client' => $client,
			'case' => $case,
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,	
      'settings' => $this->settings,
      'fs' => $this->firm_stripe, 
      'threads' => $this->threads,
		]);
	}

	public function invoice_pdf_download(Request $request, $id)
	{
		$invoice = Invoice::where('invoice_uuid', $id)->first();
		$firm = Firm::where('id', $this->settings->firm_id)->first();
		$case = LawCase::where('case_uuid', $invoice->invoicable_id)->first();
		$client= Contact::where('case_id', $case->id)->first();

		$pdf = \PDF::loadView('pdf.invoice', ['invoice' => $invoice, 'firm' => $firm, 'client' => $client]);

		return $pdf->download('invoice_'.$invoice->invoice_uuid.'.pdf');

	}

}