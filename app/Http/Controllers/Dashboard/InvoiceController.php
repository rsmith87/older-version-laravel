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
use App\Notifications\InvoiceCreatedNotification;
use Faker\Factory;
use Carbon;
use SanderVanHooft\Invoicable\MoneyFormatter;


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
			return $next($request);
		});
	}

  
	public function index(Request $request)
	{
		if(!$this->user->hasRole('client')){
			$orders = Order::where('user_id', $this->user['id'])->with('invoices')->get();
		} 
		else {
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$orders = Order::where('client_id', $contact->id)->get();
		}

		return view('dashboard/invoices', [
			'user' => $this->user,  
			'theme' => $this->settings->theme,
			'orders' => $orders,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
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
		$case = LawCase::where('id', $data['case_id'])->first();
    $case_hours = CaseHours::where('case_id', $data['case_id'])->get();
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
    
    $mycase = Order::where(['user_id' => $this->user['id'], 'case_id' => $case->id])->first();

		if(count($mycase) > 0){
			$orig_amount = $mycase->amount + floatval($data['amount']);
      LawCase::where('id', $case->id)->update(['order_id' => $mycase->id]);
      
		} else {
      $orig_amount = 0;
    }
    
    
    $email_send = $client->sendTaskDueReminder($client);
    
		Order::updateOrCreate([
			'case_id' => $data['case_id'],
		],
		[
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
			'invoicable_id' => $data['case_id'],
			'invoice_type' => 'app_client',
			'tax' => 0,
			'total' => $data['amount'],
			'currency' => 'USD',
			'status' => 'invoiced',
			'receiver_info' => $client->first_name . ' ' . $client->last_name,
			'sender_info' => $this->user['name'] . '<br />' . 'Re: '. $case->name,
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
		$invoice = Invoice::where('id', $id)->with('invoicelines')->first();
		$case = LawCase::where('id', $invoice->invoicable_id)->with('contacts')->first();
		foreach($case->Contacts as $contact){
			if($contact->is_client === 1){
				$client = $contact;
			} 
		}

		return view('vendor.invoicable.receipt', [
      'user' => $this->user,
			'invoice' => $invoice,
			'client' => $client,
			'case' => $case,
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,			
		]);
	}

}

