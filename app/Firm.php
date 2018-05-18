<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
 
  
    protected $table = "firm";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address_1', 'address_2', 'city','state', 'zip', 'email', 'phone', 'fax'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
     
    ];
  
    public function documents()
    {
      return $this->hasMany('App\Document', 'firm_id');
    }  
    public function users()
    {
      return $this->hasMany('App\User', 'f_id');
    }
  
    public function notes()
    {
        return $this->hasMany('App\Note', 'id', 'firm_id');
    }
  
   public function stripe()
   {
     return $this->hasOne('App\FirmStripe', 'firm_id');
   }
}
