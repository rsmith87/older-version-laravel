<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseHours extends Model
{
    protected $fillable = [
      'case_id',
      'hours',
      'note',
    ];
}
