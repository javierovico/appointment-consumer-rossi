<?php

namespace App\Console;

use App\Models\RossiInterno\CronHistorial;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule
            ->command("rossi:insert-mas")
            ->timezone('America/Asuncion')
            ->everyThreeHours()
            ->withoutOverlapping(300)
            ->runInBackground()
            ->onFailure(function () {
                CronHistorial::registrarEvento(-1,0,0, CarbonImmutable::now());
            });
        ;
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
