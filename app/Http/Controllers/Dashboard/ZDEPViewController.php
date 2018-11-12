<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Firm;
use App\LawCase;
use App\Contact;
use App\Task;
use App\Document;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->user = \Auth::user();
  }
  
  public function pull_data($type, Request $request)
  {
    
  }
    
}
