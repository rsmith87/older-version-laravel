<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
use Webpatser\Uuid\Uuid;
use App\Mail\InvoiceCreated;


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
			$invoices= Invoice::where(['user_id' => $this->user['id'], 'paid' => 0])->get();
		} 
		else {
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$invoices = Order::where('client_id', $contact->id)->get();
		}
		return view('dashboard/invoices', [
			'user' => $this->user,  
			'theme' => $this->settings->theme,
			'invoices' => $invoices,
			'firm_id' => $this->settings->firm_id,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
            'settings' => $this->settings,
		]);
	}

    public function paid_invoices(Request $request)
    {
            $invoices= Invoice::where(['user_id' => $this->user['id'], 'paid' => 1])->get();


        return view('dashboard/invoices', [
            'user' => $this->user,
            'theme' => $this->settings->theme,
            'invoices' => $invoices,
            'firm_id' => $this->settings->firm_id,
            'table_color' => $this->settings->table_color,
            'table_size' => $this->settings->table_size,
            'settings' => $this->settings,
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

    //create public accessible invoice access page as well
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


		$invoice = Invoice::updateOrCreate([
			'id' => $invoice_id,
		],
		[
            'invoice_uuid' => $invoice_uuid,
			'invoicable_id' => $data['case_uuid'],
			'invoicable_type' => 'app_client',
			'description' => $data['invoice_description'],
			'due_date' => $data['invoice_date'] != "" ? \Carbon\Carbon::parse($data['invoice_date'])->format('Y-m-d H:i:s') : "0000-00-00 00:00:00",
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
		////$invoice = Invoice::where('invoice_uuid', $invoice_uuid)->first();
        //$email_send = $client->sendTaskDueReminder($client);
       //// Mail::to($client->email)->send(new InvoiceCreated($invoice));


		InvoiceLine::updateOrCreate([
			'id' => $invoice_line_id,
		],[
			'invoice_id' => $invoice_id,
			'amount' => $data['amount'],
			'tax' => 0,
			'tax_percentage' => 0,
			'description' => $client->id . ": " .$client->first_name . " " . $client->last_name,
		]);

		return redirect('/dashboard/invoices/invoice/'.$invoice->invoice_uuid)->with('message', 'Invoice created successfully!');
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
		]);
	}

	public function send_invoice(Request $request, $id)
	{
		$data = $request->all();
        $firm_id = $data['firm_id'];

        $firm = Firm::where('id', $this->settings->firm_id)->first();

        $invoice = Invoice::where('invoice_uuid', $id)->first();
        //$invoice->invoicable_id references lawcase
        $case = LawCase::where('case_uuid', $invoice->invoicable_id)->first();

        $client = Contact::where(['case_id' => $case->id, 'is_client' => 1])->first();
        /*
        /nonuser/payment/firm/{firm_id}/invoice/{invoice_uuid}
        */



        Mail::to($client->email)->send(new InvoiceCreated($invoice, $firm, $client));

        //generate email and send email
        //or create modal with secure generated link to send to client email
        return redirect('/nonuser/payment/firm/'.$firm_id.'/invoice/'.$id)->with('status', 'Invoice sent successfully');
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