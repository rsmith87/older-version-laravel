<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DateController extends Controller
{

  public function fix_date($dts, $dte)
  {
    $d = Carbon\Carbon::parse($dts)->format('Y-m-d');
    $dt = Carbon\Carbon::parse($d. " " . $dte.":00", 'America/Chicago')->format('Y-m-d H:i:s');
    return $dt;

  }
}
