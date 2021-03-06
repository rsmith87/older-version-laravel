<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Contact;
use App\LawCase;
use Carbon\Carbon;
use App\Charts\ClientsByMonth;


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


      return $next($request);
    });
  }

  public function index() {
		$chart_array = [];
    $clients = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->get();
    $clients_full = Contact::where(['is_client' => '1', 'firm_id' => $this->settings->firm_id])->get();
		$chart = new ClientsByMonth();
		//dd($clients);

	  foreach($clients_full as $data){
		  $n_data = [];
		  array_push($n_data,$data['month'],$data['count']);
		  array_push($chart_array,$n_data);
	  }
	  //dd($chart_array);
	  $chart->dataset('Clients', 'bar', $chart_array);

    return view('dashboard/reports/clients', [
        'clients' => $clients,
        'user' => $this->user,
        'firm_id' => $this->settings->firm_id,
        'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
        'settings' => $this->settings,
        'chart' => $chart,
	      'clients_full' => $clients_full,
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




    $chart = \Lava::LineChart('MyCases', $casesTable);

    return view('dashboard/reports/cases', [
        'cases' => $cases,
        'user' => $this->user,
        'firm_id' => $this->settings->firm_id,
        'settings' => $this->settings,
        'chart' => $chart,
    ]);
  }

	public function dashboard(Request $request)
	{
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Name');
		$datatable->addNumberColumn('Donuts Eaten');
		$datatable->addRows([
			['Michael', 5],
			['Elisa', 7],
			['Robert', 3],
			['John', 2],
			['Jessica', 6],
			['Aaron', 1],
			['Margareth', 8],
		]);
		$pieChart = \Lava::PieChart('Donuts', $datatable, [
			'width' => 400,
			'pieSliceText' => 'value',
		]);
		$filter = \Lava::NumberRangeFilter(1, [
			'ui' => [
				'labelStacking' => 'vertical',
			],
		]);
		$control = \Lava::ControlWrapper($filter, 'control');
		$chart = \Lava::ChartWrapper($pieChart, 'chart');
		\Lava::Dashboard('Donuts')->bind($control, $chart);
		return view('dashboard/reports/cases', [
			'cases' => $cases,
			'user' => $this->user,
			'firm_id' => $this->settings->firm_id,
			'settings' => $this->settings,
			'chart' => $chart,
			'control' => $control,
			'chart' => $chart,
			'piechart' => $piechart,
		]);
	}

}
