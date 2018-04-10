<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LawCase extends Model
{
    protected $table = 'case';
    public $timestamps = false;

  
    protected $fillable = [
        'id',
        'status', 
        'number',  
        'name', 
        'description', 
        'court_name', 
        'opposing_councel', 
        'claim_reference_number', 
        'location',
        'open_date',
        'close_date',
        'created_at',
        'statute_of_limitations',      
        'created_at', 
        'open_date', 
        'close_date', 
        'statute_of_limitations', 
        'is_billable', 
        'billing_type', 
        'rate_type',
        'billing_rate',
        'hours',
        'order_id',
        'firm_id', 
        'u_id'
    ];
  
    protected $dates = [

    ];
  
      /**
     * Get all of the contacts for the case.
     */
    public function contacts()
    {
        return $this->hasMany('App\Contact', 'case_id');
    }
    /**
     * Get all of the contacts for the case.
     */
    public function documents()
    {
        return $this->hasMany('App\Document', 'case_id');
    }
  
    public function tasks()
    {
      return $this->hasMany('App\Task', 'c_id');
    }
  
    public function order()
    {
      return $this->hasOne('App\Order', 'case_id', 'id');
    }
   
  
  
  
}
