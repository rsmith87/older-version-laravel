<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Task;
use App\Notifications\TaskDueReminder;
use Carbon\Carbon;

class TaskEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the task reminder email to users when within 1 hour of due date.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      //gets tasks that are due in now minus 1 hour, but cron runs every 15m
      $tasks = Task::get();
      $now_time = Carbon::now();
      foreach($tasks as $task){
        if(Carbon::parse($task->due) > $now_time->subHour(1)){
          $email_send = $task->sendTaskDueReminder($task);
        }
      }
    }
}
