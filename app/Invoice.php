<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Invoice extends Model
{
  use SoftDeletes;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id',
    'invoicable_id',
    'invoicable_type',
    'invoice_uuid',
	  'description',
    'tax',
    'total',
    'currency',
    'reference',
    'receiver_info',
    'sender_info',
    'payment_info',
    'note',
    'created_at',
    'updated_at',
    'user_id',
  ];
  
  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];
    
  public function lawcase()
  {
    return $this->hasOne('App\LawCase', 'invoicable_id', 'id');
  }
  
  public function client()
  {
    return $this->hasOne('App\Contact', 'client_id', 'id');
  }
  
  public function order()
  {
    return $this->hasOne('App\Order', 'reference', 'reference');
  }
  
  public function invoicelines()
  {
    return $this->hasMany('App\InvoiceLine', 'invoice_id', 'id');
  }
  

    
}
