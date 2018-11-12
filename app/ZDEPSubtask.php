<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
   protected $table = 'subtask';
  
   protected $fillable = [
     'id', 
     'user_id',
     'subtask_name',
     'subtask_description',
     't_id',
     'assigned',
     'due',
     'complete',
   ];




}
