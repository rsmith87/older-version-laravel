<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contact";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'prefix', 'first_name', 'last_name', 'company', 'company_title', 'phone', 'email', 'address', 'case_id', 'firm_id', 'is_client', 'user_id'
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
        return $this->hasMany('App\Document', 'contact_id', 'id');
    }
    public function documents_client()
    {
        return $this->hasMany('App\Document', 'client_id', 'id');
    }
  
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
  
  
}
