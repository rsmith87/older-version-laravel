<?php

namespace App\Handlers;
//use App\Settings;

class ConfigHandler
{
    public function userField()
    {
        return auth()->user()->id;
    }
      
}
