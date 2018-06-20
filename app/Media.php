<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
 
  protected $dates = [ 'created_at', 'updated_at', 'deleted_at'];

  public $fillable = [
    'uuid',
    'name',
    'file_name',
    'mime_type',
    'disk',
    'size',
    'user_id',
  ];
  
  
  public function user()
  {
    $this->hasOne('App\User', 'user_id', 'id');
  }
  
  public function relates()
  {
    $this->hasOne('App\MediaRelationship', 'uuid', 'media_uuid');
  }
  
  public function shares()
  {
    $this->hasMany('App\MediaShare', 'uuid', 'media_uuid');
  }
  
  
 
}
