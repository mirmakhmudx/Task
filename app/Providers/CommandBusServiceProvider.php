<?php

namespace App\Providers;

use App\CommandBus;
use App\Commands\User\Auth\Register;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class);

        CommandBus::register([
            Register\Command::class => Register\Handler::class,
        ]);
    }
}
