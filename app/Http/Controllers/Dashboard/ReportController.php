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

class ReportController extends Controller {

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware(function ($request, $next) {
      $this->user = \Auth::user();
      if (!$this->user) {
        return redirect('/login');
      }
      if (!$this->user->hasPermissionTo('view reports')) {
        return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
      }
      $this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();

      return $next($request);
    });
  }

  public function index() {

    $clients = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->get();

    //print_r($clients);
    //->whereDate('created_at', '<=', Carbon::today()->toDateString())
    //$lava = \Lava::

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




    return view('dashboard/reports/clients', [
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

  public function cases() {
    //inefficiencies in that we ne need multiple calls to the same model due to needing actual data and counted group by data
    //TODO: refactor at some point
    $cases = LawCase::where(['u_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id])->get();

    $count_cases = LawCase::where(['u_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id])->get()->groupBy(function (LawCase $item) {
      return (int) $item->created_at->format('m');
    });



    $casesTable = \Lava::DataTable();  // Lava::DataTable() if using Laravel

    $casesTable->addStringColumn('Month')
            ->addNumberColumn('Cases')
            ->addRow(['January', isset($count_cases[1]) ? $count_cases[1] : 0])
            ->addRow(['February', isset($count_cases[2]) ? $count_cases[2] : 0])
            ->addRow(['March', isset($count_cases[3]) ? $count_cases[3] : 0])
            ->addRow(['April', isset($count_cases[4]) ? $count_cases[4] : 0])
            ->addRow(['May', isset($count_cases[5]) ? $count_cases[5] : 0])
            ->addRow(['June', isset($count_cases[6]) ? $count_cases[6] : 0])
            ->addRow(['July', isset($count_cases[7]) ? $count_cases[7] : 0])
            ->addRow(['August', isset($count_cases[8]) ? $count_cases[8] : 0])
            ->addRow(['September', isset($count_cases[9]) ? $count_cases[9] : 0])
            ->addRow(['October', isset($count_cases[10]) ? $count_cases[10] : 0])
            ->addRow(['November', isset($count_cases[11]) ? $count_cases[11] : 0])
            ->addRow(['December', isset($count_cases[12]) ? $count_cases[12] : 0]);




    $chart = \Lava::BarChart('MyCases', $casesTable);

    return view('dashboard/reports/cases', [
        'cases' => $cases,
        'user' => $this->user,
        'firm_id' => $this->settings->firm_id,
        'settings' => $this->settings,
        'fs' => $this->firm_stripe,
        'chart' => $chart,
        'threads' => $this->threads,
    ]);
  }

}
