<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id', 
      'task_list_name', 
      'user_id',
      'f_id', 
      'contact_client_id', 
      'c_id',
      'assigned', 
      'due',
      'created_at',
      'updated_at',
    ];
  
    public function contact_client()
    {
      return $this->hasOne('App\Contact', 'contact_client_id', 'id');
    }
 
    public function task()
    {
      return $this->hasMany('App\Task', 'task_list_id');
    }
  
    public function dashboard_task()
    {
      return $this->hasMany('App\Task', 'task_list_id')->take(5); 
    }
}