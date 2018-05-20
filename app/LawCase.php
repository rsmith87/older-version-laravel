<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class LawCase extends Model
{
    use Searchable;
  
    protected $table = 'case';

  
    protected $fillable = [
        'id',
        'case_uuid',
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
    public function client()
    {
        return $this->hasOne('App\Contact', 'case_id')->withDefault([
          'is_client' => 1,
        ]);
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
      return $this->hasMany('App\TaskList', 'c_id');
    }
  
    public function notes()
    {
      return $this->hasMany('App\Note', 'id', 'case_id');
    }
  
    public function order()
    {
      return $this->hasOne('App\Order', 'case_id', 'id');
    }
   
    public function case_hours()
    {
      return $this->hasMany('App\CaseHours', 'id', 'case_id');
    }
  
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get associated timers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    /**
     * Get my projects
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMine($query)
    {
        return $query->where('u_id', auth()->user()->id);
    }  
}
