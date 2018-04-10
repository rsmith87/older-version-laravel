<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\View;
use Laravel\Passport\HasApiToken;
use Illuminate\Database\Eloquent\Model;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification as Notification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\RoutesNotifications;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\ResetEmailNotification;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable 
  
{
use Messagable, Notifiable, Billable, HasRoles;
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

  public static function generatePassword()
  {
    return bcrypt(str_random(35));
  }

  public function sendPasswordResetNotification($user)
  {
    // Send email
    $user->notify(new ResetPasswordNotification($user));
  }
}
