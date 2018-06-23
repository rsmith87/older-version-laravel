<?php

namespace App\Handlers;

class ConfigHandler
{
    public function userField()
    {
        return auth()->user()->id;
    }
    
    public function firmField()
    {
      $firm_id = Settings::where('user_id', auth()->user()->id)->first();
      return $firm_id->firm_id;
    }     
}
