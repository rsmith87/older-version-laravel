<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
      'id',
      'case_id',
      'contact_client_id',
      'note',
      'user_id',
      'firm_id',
      'created_at',
      'updated_at',
    ];
      
    public function user()
    {
      return $this->hasOne('App\User', 'user_id');
    }
  
    public function firm()
    {
      return $this->hasOne('App\Firm', 'firm_id');
    }
  
    public function contact_client()
    {
      return $this->hasOne('App\Contact', 'contact_client_id');
    }
  
    public function lawcase()
    {
      return $this->hasOne('App\Case', 'case_id');
    }
}
