<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
  
  public $fillable = [
    'uuid',
    'model',
    'model_id',
    'name',
    'file_name',
    'mime_type',
    'disk',
    'size',
    'user_id',
    'created_at',
    'updated_at',
  ];
  
 
}
