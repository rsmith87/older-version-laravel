<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = "views";
    public $timestamps = false;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'view_type', 'view_data', 'u_id'
    ];
  
    protected $hidden = [
      'id'
    ];
  
 
}
