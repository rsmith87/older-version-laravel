<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category_task_link';
  
    protected $fillable = [
      'category_id',
      'task_id',
    ];
    
    public function task()
    {
      return $this->hasOne('App\Task', 'task_id');
    }
    
    public function category()
    {
      return $this->hasOne('App\Category', 'category_id');
    }
}
