<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaRelationship extends Model
{
  use SoftDeletes;
  
   public $fillable = [
    'media_uuid',
    'model',
    'model_id',
    'user_id',

  ];
   
  protected $dates = [     
    'created_at',
    'updated_at',
    'deleted_at',
  ];
  
  public function media_instance()
  {
    $this->belongsTo('App\Media', 'media_uuid', 'uuid');
  }
  
  //user who created the relatinoship
  public function user()
  {
    $this->hasOne('App\User', 'user_id', 'id');
  }
  
  public function model()
  {
    $this->hasOne('App\Model', 'model');
  }
}
