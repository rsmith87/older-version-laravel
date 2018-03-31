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
     'task_descrioption',
     'f_id',
     'c_id',
     't_id',
     'assigned',
     'st_id',
     'due',
   ];

    public function task()
    {
      return $this->belongsToOne('App\Task', 'id', 't_id');
    }


}
