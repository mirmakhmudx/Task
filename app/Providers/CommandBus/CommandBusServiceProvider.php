<?php

namespace App\Providers\CommandBus;

use App\CommandBus;
use App\Console\Commands\User\Auth\Register\Command as RegisterCommand;
use App\Console\Handlers\User\Auth\Register\Handler as RegisterHandler;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class);

        CommandBus::register([
            RegisterCommand::class => RegisterHandler::class,
        ]);
    }
}
