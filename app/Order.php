<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
   protected $fillable = [
    'id',
    'order_uuid',
    'amount',
    'amount_remaining',
    'client_id',     
    'case_id',    
    'firm_id',
    'user_id',
    'reference',
  ];
  
  public function firm()
  {
    return $this->hasOne('App\Firm', 'id', 'firm_id');
  }
  
  public function lawcase()
  {
    return $this->hasOne('App\LawCase', 'id', 'case_id');
  }
  
  public function client()
  {
    return $this->hasOne('App\Contact', 'id', 'client_id');
  }  
  
  public function invoices()
  {
    return $this->hasMany('App\Invoice', 'invoicable_id', 'case_uuid');
  }
  
}
