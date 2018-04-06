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


class User extends Authenticatable 
  
{
    use Messagable, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'name', 
      'email', 
      'f_id',
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
      // Generate random string and encrypt it. 
      return bcrypt(str_random(35));
    }
  
    public static function sendWelcomeEmail($user)
    {
      // Generate a new reset password token
      Notification::send($user, new InvoicePaid());

      
      // Send email
      //$user->notify(new ResetPasswordNotification($user));

    }
}
