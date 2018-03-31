<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $table = 'events';
  
  protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'start_time',
    'end_time',
    'u_id',
    'co_id',
    'c_id',
    'f_id'
  ];
  
    
  public function user()
  {
    return $this->hasOne('App\User', 'u_id', 'id');
  }
  
  public function lawCase()
  {
    return $this->hasOne('App\LawCase', 'c_id', 'id');
  }
  
  public function contact()
  {
    return $this->hasOne('App\Contact', 'co_id', 'id');
  }
  
  public function firm()
  {
    return $this->hasOne('App\Firm', 'f_id', 'id');
  }
}
