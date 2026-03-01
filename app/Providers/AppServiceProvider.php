<?php

namespace App\Providers;

use App\Commands\DocitCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Config::set('hyde.footer', false);

        $this->commands([
            DocitCommand::class,
        ]);
    }
}
