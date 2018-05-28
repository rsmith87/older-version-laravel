<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Contact;
use App\LawCase;
use App\FirmStripe;
use Carbon\Carbon;
use App\Charts\ByMonth;

class ReportController extends Controller
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
			if(!$this->user->hasPermissionTo('view reports')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}					
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			return $next($request);
		});
	}
  
  public function index()
  {
    
   $clients = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->get();

   
    //->whereDate('created_at', '<=', Carbon::today()->toDateString())
   
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    /*$clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();    
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get(); */
    
    $chart = new ByMonth;
    $chart->dataset('Clients', 'bar', [count($clients_jan), 65, 84, 45, 90]);
    
    
    
    
    return view('dashboard/reports', [
      'clients' => $clients,
      'user' => $this->user, 
      'firm_id' => $this->settings->firm_id, 
      'theme' => $this->settings->theme, 
      'table_color' => $this->settings->table_color, 
      'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,
      'chart' => $chart,
    ]);
  }
  
  public function cases()
  {
    $cases = LawCase::where(['u_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id])->get();
    
  }
}
