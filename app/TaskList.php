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
      'task_list_uuid',
      'task_list_name',
	    'task_list_description',
      'user_id',
      'f_id', 
      'contact_client_id', 
      'c_id',
	    'show_dashboard',
      'assigned', 
      'due',
      'complete',
      'created_at',
      'updated_at',
    ];
  
    public function contact_client()
    {
      return $this->hasOne('App\Contact', 'contact_client_id', 'id');
    }
 
    public function task()
    {
      return $this->hasMany('App\Task', 'task_list_uuid', 'task_list_uuid');
    }
  
    public function dashboardtasks()
    {
      return $this->hasMany('App\Task', 'task_list_uuid', 'task_list_uuid'); 
    }

    public function case()
		{
			return $this->hasOne('App\Lawcase', 'id', 'c_id');
		}
}
