<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contact";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id', 
      'prefix', 
      'first_name', 
      'last_name', 
      'company', 
      'company_title', 
      'phone', 
      'email', 
      'address_1', 
      'address_2', 
      'city', 
      'state', 
      'zip', 
      'case_id', 
      'firm_id', 
      'is_client', 
      'has_login',
      'user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];
  
    public function cases()
    {
      return $this->hasOne('App\Case', 'id', 'case_id');
    }
  
    public function documents()
    {
        return $this->hasMany('App\Document', 'contact_id');
    }
  
    public function documentsclients()
    {
        return $this->hasMany('App\Document', 'client_id');
    }  
  
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
  
    public function tasks()
    {
      return $this->hasMany('App\Task', 'contact_client_id');
    }
    
    public function user_account()
    {
      return $this->hasOne('App\User', 'id', 'has_login');
    }
  
  
  
}
