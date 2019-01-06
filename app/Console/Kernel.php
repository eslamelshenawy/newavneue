<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Test;
use App\User;
use App\Lead;
use App\ToDo;
use App\AdminNotification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        
          $schedule->call(function () {
                  $todo=ToDo::where('due_date','<',strtotime(date('Y-m-d')))->get();
            foreach ($todo as $row)
            {
                $lead=Lead::find($row->leads);
                $tokens=User::where('refresh_token', '!=', '')->where('id',$row->user_id)->pluck('refresh_token')->toArray();
                $msg = array(
                    'title' => __('admin.leads', [], 'en'),
                    'body' => 'you have tasks don not finished '.$row->to_do_type.' with '.$lead->first_name.' '.$lead->last_name,
                    'image' => 'myIcon',/*Default Icon*/
                    'sound' => 'mySound'/*Default sound*/
                );
                $not = new AdminNotification;
                $not->user_id = $row->user_id;
                $not->assigned_to = $lead->user_id;
                $not->type = 'finish_task';
                $not->type_id = $row->id;
                $not->save();
                notify1($tokens, $msg);
                $test=new Test();
                $test->name='sheno';
                $test->save();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
