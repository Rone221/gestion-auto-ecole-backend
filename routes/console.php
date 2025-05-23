<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('abonnements:check-expiration', function () {
    $this->info('Commande `abonnements:check-expiration` exécutée');
    $this->comment('Vérification des abonnements expirés effectuée.');
});
