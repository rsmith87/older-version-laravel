<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaShare extends Model
{
  use SoftDeletes;
  
    public $fillable = [
    'media_uuid',
    'user_id',
    'user_id_share_with',
    'firm_id_share_with',
  ];
    
    protected $dates = [
      'created_at',
      'updated_at',
      'deleted_at',
    ];
            
    public function user()
    {
      $this->hasOne('App\User', 'user_id', 'id');
    }
    
    public function media()
    {
      $this->belongsTo('App\Media', 'media_uuid', 'uuid');
    }
    
    public function shared_user()
    {
      $this->hasOne('App\User', 'user_id_share_with', 'id');
    }
    
    public function shared_firm()
    {
      $this->hasOne('App\Firm', 'firm_id_shared_with', 'id');
    }
}
