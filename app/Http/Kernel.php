<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $commands = [
        \App\Console\Commands\PaiementsCheckRelances::class,
        \App\Console\Commands\PaiementsApplyPenalties::class,
        \App\Console\Commands\PaiementsMarquerEnRetard::class,
        \App\Console\Commands\PaiementsRelancerRetards::class,
        \App\Console\Commands\AutoEcolesDisableUnpaid::class,
    ];
    

    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule){
        $schedule->command('auto-ecoles:disable-unpaid')->dailyAt('00:00');
        $schedule->command('paiements:check-relances')->daily();
        $schedule->command('paiements:apply-penalties')->daily();
        $schedule->command('paiements:marquer-en-retard')->dailyAt('00:00');

    // Relance automatique chaque jour à 8h du matin
        $schedule->command('paiements:relancer-retards')->dailyAt('08:00');
    }

    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,

            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
}
