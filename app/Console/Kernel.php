<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Ежечасная синхронизация
        $schedule->command('moysklad:sync')
            ->hourly()
            ->withoutOverlapping() // Чтобы не запускалось одновременно несколько раз
            ->runInBackground(); // Работает в фоне
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
