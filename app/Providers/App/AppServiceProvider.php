<?php

namespace App\Providers\App;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Services\Sms\ArraySender;
use App\Services\Sms\LoggedSms;
use App\Services\Sms\SmsSender;
use App\Services\Sms\SmsRu;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(SmsSender::class, function (Application $app) {
            $config = $app->make('config')->get('sms');
            $driver = $config['driver'] ?? 'array';

            $sender = match ($driver) {
                'sms.ru' => new SmsRu(
                    $config['drivers']['sms.ru']['app_id'],
                    $config['drivers']['sms.ru']['url'],
                ),
                default => new ArraySender(),
            };

            if ($app->environment('production')) {
                return new LoggedSms($sender, $app->make('log'));
            }

            return $sender;
        });
    }


    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}

