<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirmStripe extends Model
{
  
  protected $table = 'firm_stripe';
  
  protected $fillable = [
    'id',
    'firm_id',
    'stripe_account_id',
    'user_id',
  ];
  
  public function firm()
  {
    return $this->hasOne('App\Firm', 'firm_id');
  }
  
  public function user()
  {
    return $this->hasOne('App\User', 'user_id');
  }
  
}
