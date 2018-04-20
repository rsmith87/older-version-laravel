<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\TaskDueReminder;

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
  
    public function categories()
    {
      return $this->hasMany('App\Category', 'task_id');
    }

    public function sendTaskDueReminder($task)
    {
      // Send email
      $user = User::where('id', $task->user_id)->first();
      $user->notify(new TaskDueReminder($task));
    }   
}
