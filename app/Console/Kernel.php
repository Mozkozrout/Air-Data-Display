<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /**
         * This is the code that would periodically refresh the database with data from your API, tho my hosting doesn't support custom crom job.
         * I would also normally set it to maybe every 5 minutes or so but i didn't want to spam your API too much.
         */
        $schedule->call('App\Http\Controllers\DataController@fetchData')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
