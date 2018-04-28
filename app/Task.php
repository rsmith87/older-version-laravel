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
      'id', 
      'task_name', 
      'task_description', 
      'task_list_id',
      'due',
      'assigned',
      'contact_client_id',
      'created_at',
      'updated_at',
    ];
  
    public function subtasks()
    {
      return $this->hasMany('App\Subtask', 't_id', 'id');
    }
  
    public function categories()
    {
      return $this->hasMany('App\Category', 'task_id', 'id');
    }

    public function sendTaskDueReminder($task)
    {
      // Send email
      $user = User::where('id', $task->user_id)->first();
      $user->notify(new TaskDueReminder($task));
    }   
}
