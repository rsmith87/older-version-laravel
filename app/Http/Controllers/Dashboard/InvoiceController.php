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
            $this->user_id = \Auth::id();
            $this->settings = Settings::where('user_id', $this->user_id)->first();
            return $next($request);
        });
    }
  
	public function index(Request $request)
	{
		$orders = Order::where('user_id', $this->user_id)->with('invoices')->get();
		return view('dashboard/invoices', [
			'user_name' => $this->user['name'],  
			'theme' => $this->settings->theme,
			'orders' => $orders,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
		]);
  }
	
	public function view(Request $request, $id)
	{
		
		
		$firm = Firm::where('id', $this->settings->firm_id)->first();


	}
	
	public function create(Request $request)
	{		

		$data = $request->all();
		
		//find id to use
		if(array_key_exists('id', $data)){
      $id = $data['id'];
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
		$firm = Firm::where('id', $this->settings->firm_id)->first();
		$client = Contact::where('id', $data['client_id'])->first();
		$amount = $data['amount'];
		$firm_address = $firm->address_1 . " " . $firm->address_2 . " " . $firm->city 
				. " " . $firm->state . " " . $firm->zip;
		$mycases = Order::where('user_id', $this->user_id)->get();
		

		//create an order and an invoice when it is converted over
		//TODO: Need to reference the total amount so we can see how much is owed on the order 
		//TODO: -- add a hidden field of total amount so it can pass over AND make it where update on "add hours" action on case IF case_id = invoicable_id
		//TODO: -- after checks to see if it exists first
		//Order can have multiple invoices
		//invoices can have multiple lines - not fully set up only using 1 line
		//one order per client
		
		//$ordered_case = LawCase::where('order_id', $)
		$order = Order::updateOrCreate([
				'id' => $id,
		],
		[
			'amount' => floatval($data['amount']),
			'total_amount' => floatval($data['total_amount']),
			'case_id' => $data['case_id'],
			'firm_id' => $this->settings->firm_id,
			'client_id' => $data['client_id'],
			'user_id' => \Auth::id(),
		]);
		
		$invoice = Invoice::updateOrCreate([
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
			'sender_info' => \Auth::user()->name . '<br />' . 'Re: '. $case->name,
			'payment_info' => 'stripe',
			'note' => '',
		]);
		
		InvoiceLine::updateOrCreate([
			'id' => $invoice_line_id,
		],[
			'invoice_id' => $invoice_id,
			'amount' => $data['amount'],
			'tax' => 0,
			'tax_percentage' => 0,
			'description' => $client->id . ": " .$client->first_name . " " . $client->last_name ,
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
			'invoice' => $invoice,
			'client' => $client,
			'case' => $case,
			'user_name' => $this->user['name'],  
			'theme' => $this->settings->theme,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,			
		]);
	}
    
}

