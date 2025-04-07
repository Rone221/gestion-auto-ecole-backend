<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\PaiementsCheckRelances::class,
        \App\Console\Commands\PaiementsApplyPenalties::class,
        \App\Console\Commands\MarquerPaiementsEnRetard::class,
        \App\Console\Commands\RelancerPaiementsEnRetard::class,
        \App\Console\Commands\DisableUnpaidAutoEcoles::class,
        \App\Console\Commands\CheckAbonnementExpiration::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('auto-ecoles:disable-unpaid')->dailyAt('00:00');
        $schedule->command('paiements:check-relances')->daily();
        $schedule->command('paiements:apply-penalties')->daily();
        $schedule->command('paiements:marquer-en-retard')->dailyAt('00:00');
        $schedule->command('paiements:relancer-retards')->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
