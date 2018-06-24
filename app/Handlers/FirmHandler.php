<?php

namespace App\Handlers;
//use App\Settings;

class FirmHandler
{

    public function firmField()
    {
      $firm_id = \App\Settings::where('user_id', auth()->user()->id)->first();
      return $firm_id->firm_id;
    }     
}
