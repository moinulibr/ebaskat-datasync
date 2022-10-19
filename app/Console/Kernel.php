<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Datasync\Bigbuy\UpdateBigbuyProductTaskScheduleCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateBigbuyProductTaskScheduleCommand::class,
        //Commands\CrudGenerator::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:datasync-bigbuy-product')->dailyAt('03:00');

        //$schedule->command('update:datasync-bigbuy-product')->dailyAt('08:25');//6 hour less from bd
        //$schedule->command('update:datasync-bigbuy-product')->twiceDaily(6, 16);
        //$schedule->command('update:datasync-bigbuy-product')->dailyAt('12:49');
        //$schedule->command('update:datasync-bigbuy-product')->everyMinute();
        //$schedule->command('update:datasync-bigbuy-product')->twiceDaily(9, 17);
        //$schedule->command('update:datasync-bigbuy-product')->hourly();
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
