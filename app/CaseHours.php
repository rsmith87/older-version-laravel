<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseHours extends Model
{
    protected $fillable = [
      'case_uuid',
      'hours',
      'note',
      'timer_id',
    ];
}
