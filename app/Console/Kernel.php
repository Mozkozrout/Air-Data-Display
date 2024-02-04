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
         * Since this needs the api token which is stored inside the user table in the database, it won't work correctly
         * when the server tries to call this function and user isn't logged in. It will probably return some sort of an
         * error and the data won't get refreshed so the user will see the old data from the database.
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
