<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommLog extends Model
{
    protected $fillable = [
      'id',
      'type',
      'type_id',
      'comm_type',
      'log',
    ];
}
