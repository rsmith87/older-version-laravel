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


		$mycases = Order::where('user_id', $this->user_id)->get();

		return view('dashboard/invoices', [
			'user_name' => $this->user['name'],  
			'theme' => $this->settings->theme,
			'my_cases' => $mycases,
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
		$case = LawCase::where('id', $data['case_id'])->first();
		$firm = Firm::where('id', $this->settings->firm_id)->first();
		$client = Contact::where('id', $data['client_id'])->first();
		$amount = $data['amount'];
		$firm_address = $firm->address_1 . " " . $firm->address_2 . " " . $firm->city 
				. " " . $firm->state . " " . $firm->zip;
		$mycases = Order::where('user_id', $this->user_id)->get();
		
		$order = Order::updateOrCreate([
			'id' => $data['order_id'],
		],
		[
			'invoicable_id' => $data['case_id'],
			
		]);
		
		$order = new Order;
		$order->amount = $data['amount'];
		$order->case_id = $data['case_id'];
		$order->firm_id = $this->settings->firm_id;
		$order->client_id = $data['client_id'];
	  $order->user_id = $this->user_id;
		$order->save();
		
		$invoice = new Invoice;
		
		
		
		return redirect('/dashboard/invoices')->with('message', 'Your invoice is being generated and a link to the invoice will show when it is ready.');
	}
	
	public function invoice_view($id)
	{

	}
    
}

