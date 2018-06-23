<?php

namespace App\Handlers;

class ConfigHandler
{
    public function userField()
    {
        return \Auth::id();
    }
    
    public function firmField()
    {
      $firm_id = Settings::where('user_id', \Auth::id())->first();
      return $firm_id->firm_id;
    }     
}
