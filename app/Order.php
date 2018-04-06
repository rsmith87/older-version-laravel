<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\Invoicable\IsInvoicable\IsInvoicableTrait;

class Order extends Model
{
  
   use IsInvoicableTrait; // enables the ->invoices() Eloquent relationship
  
   protected $fillable = [
    'id',
    'cost',
    'client_id',     
    'case_id',    
    'firm_id',
    'user_id',
    'reference',
  ];
  
  public function firm()
  {
    return $this->hasOne('App\Firm', 'firm_id', 'id');
  }
  
  public function lawcase()
  {
    return $this->hasOne('App\LawCase', 'case_id');
  }
  
  public function client()
  {
    return $this->hasOne('App\Contact', 'client_id');
  }  
  
  public function invoices()
  {
    return $this->hasMany('App\Invoice', 'reference', 'reference');
  }
}
