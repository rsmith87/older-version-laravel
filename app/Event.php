<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\EventConfirmNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Notifications\Notification as Notification;

class Event extends Model
{
  
  use Notifiable;  
  
  protected $table = 'events';
  
  protected $fillable = [
    'id',
    'uuid',
    'name',
    'type',
    'description',
    'start_date',
    'end_date',
    'u_id',
    'co_id',
    'c_id',
    'f_id',
    'approved',
  ];
  
    
  public function user()
  {
    return $this->hasOne('App\User', 'id', 'u_id');
  }
  
  public function lawCase()
  {
    return $this->hasOne('App\LawCase', 'id', 'c_id');
  }
  
  public function contact()
  {
    return $this->hasOne('App\Contact', 'id', 'co_id');
  }
  
  public function firm()
  {
    return $this->hasOne('App\Firm', 'id', 'f_id');
  }
  

}
