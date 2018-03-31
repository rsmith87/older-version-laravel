<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
     protected $fillable = [
      'user_id',
      'start', 
      'timer', 
      'stop', 
      'request_time',
      'case_id',
      'created_at', 
      'updated_at',
     ];
       
}
