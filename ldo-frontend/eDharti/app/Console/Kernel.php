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
        // $schedule->command('inspire')->hourly();
        $schedule->command('clear:otps')->hourly();
        $schedule->command('reset:rgr-status')
            ->cron('0 0 1 1 *'); // At 00:00 on January 1st every year
        $schedule->command('app:change-registration-status')->dailyAt('11:00');
        $schedule->command('app:change-application-status')->dailyAt('11:00');
        $schedule->command('app:change-public-grevance-status')->dailyAt('11:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
