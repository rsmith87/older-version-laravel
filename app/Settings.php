<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
  
   protected $table = 'settings';
   public $timestamps = false;
  
   protected $fillable = [
      'user_id',
      'bar_number', 
      'state_of_bar', 
      'practice_areas', 
      'firm_id', 
      'theme',
      'update_pass',
      'is_deleted',
      'table_color',
      'table_size',
      'tz',
   ];
}
