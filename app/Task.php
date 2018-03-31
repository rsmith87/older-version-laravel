<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'task_name', 'user_id','task_description', 'f_id', 'contact_client_id', 'c_id','assigned', 'due'
    ];
  
    public function subtasks()
    {
      return $this->hasMany('App\Subtask', 't_id', 'id');
    }

}
