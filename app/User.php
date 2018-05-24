<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\EventConfirmNotification;
use App\Notifications\EventDenyNotification;
use Laravel\Cashier\Billable;
use Cmgmyr\Messenger\Traits\Messagable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
  
{
use HasApiTokens, Notifiable, Billable, HasRoles, Messagable;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id',
    'name', 
    'email', 
    'provider',
    'provider_id',
    'password', 
    'created_at',
  ];
  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [
    'remember_token',
  ];

  public function views()
  {
    return $this->hasMany('App\View','user_id');
  }

  public function tasks()
  {
    return $this->hasMany('App\Task', 'user_id');
  }

  public function firm()
  {
    return $this->hasOne('App\Firm', 'firm_id');
  }

  public function settings()
  {
    return $this->hasOne('App\Settings', 'user_id');
  }
  
  public function timer()
  {
    return $this->hasOne('App\Timer', 'user_id');
  }
  
  public function notes()
  {
    return $this->hasMany('App\Note', 'id', 'user_id');
  }
  
  public function messages()
  {
      return $this->hasMany(Message::class);
  }
  
  public static function generatePassword()
  {
    return bcrypt(str_random(35));
  }

  public function sendPasswordResetNotification($user)
  {
    // Send email
    $user->notify(new ResetPasswordNotification($user));
  }

  public function sendEventConfirmNotification($user)
  {
    // Send email
    $user->notify(new EventConfirmNotification($user));
  }    

  public function sendEventDenyNotification($user)
  {
    // Send email
    $user->notify(new EventDenyNotification($user));
  }    
  
}
