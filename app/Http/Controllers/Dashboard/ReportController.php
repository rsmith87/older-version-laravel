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
use App\Thread;



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
      $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
      
			return $next($request);
		});
	}
  
  public function index()
  {
    
    $clients = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->get();

    print_r($clients);
    //->whereDate('created_at', '<=', Carbon::today()->toDateString())
    $lava = \Lava::
   
    $clients_jan = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '<=', Carbon::now()->subMonth())->get();
    $clients_feb = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_mar = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_apr = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_may = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_jun = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();    
    $clients_jul = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_aug = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_sep = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_oct = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_nov = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    $clients_dec = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->where('created_at', '>=', Carbon::now()->subMonth())->get();
    
    $chart = new ByMonth;
    $chart->dataset('Clients', 'bar', [count($clients_jan), $clients_feb, $clients_mar, $clients_apr, $clients_may, $clients_jun, $clients_jul,
        $clients_aug, $clients_sep, $clients_oct, $clients_nov, $clients_dec]);
    
    
    
    
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
      'threads' => $this->threads,
    ]);
  }
  
  public function cases()
  {
    $cases = LawCase::where(['u_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id])->get();
    
    $stocksTable = \Lava::DataTable();  // Lava::DataTable() if using Laravel

    $stocksTable->addDateColumn('Day of Month')
                ->addNumberColumn('Projected')
                ->addNumberColumn('Official');

    // Random Data For Example
    for ($a = 1; $a < 30; $a++) {
        $stocksTable->addRow([
          '2015-10-' . $a, rand(800,1000), rand(800,1000)
        ]);
    }



    
  }
}
