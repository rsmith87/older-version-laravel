<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseHours extends Model
{
    protected $fillable = [
      'case_uuid',
      'timespan',
      'note',
      'timer_id',
    ];
}
