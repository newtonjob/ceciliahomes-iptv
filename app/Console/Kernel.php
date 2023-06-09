<?php

namespace App\Console;

use App\Jobs\DownloadFoodImages;
use App\Jobs\SyncFoods;
use App\Jobs\SyncOrders;
use App\Jobs\SyncRooms;
use App\Jobs\SyncVideoOrders;
use App\Jobs\SyncVideos;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(SyncRooms::class)->everyMinute();
        $schedule->job(SyncFoods::class)->everyMinute();
        $schedule->job(DownloadFoodImages::class)->everyMinute();
        $schedule->job(SyncOrders::class)->everyMinute();
        $schedule->job(SyncVideos::class)->everyMinute();
        $schedule->job(SyncVideoOrders::class)->everyMinute();
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
