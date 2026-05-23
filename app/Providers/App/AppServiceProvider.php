<?php

namespace App\Providers\App;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

use App\Services\Sms\ArraySender;
use App\Services\Sms\LoggedSms;
use App\Services\Sms\SmsSender;
use App\Services\Sms\SmsRu;
use App\Http\Router\AdvertsPath;
use App\Entity\Region; // Agar sizda namespace boshqacha bo'lsa, moslab olasiz
use App\Entity\Adverts\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // SMS Xizmati singltoni
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

        // TO'G'RI ELASTICSEARCH BINDING
        $this->app->singleton(Client::class, function () {
            $hosts = explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'));

            return ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        });
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Route::bind('adverts_path', function (string $value): AdvertsPath {
            $chunks = explode('/', $value);

            $region = null;
            do {
                $slug = reset($chunks);
                if ($slug && $next = Region\Region::where('slug', $slug)->first()) {
                    $region = $next;
                    array_shift($chunks);
                }
            } while (!empty($slug) && !empty($next));

            $category = null;
            do {
                $slug = reset($chunks);
                if ($slug && $next = Category::where('slug', $slug)->first()) {
                    $category = $next;
                    array_shift($chunks);
                }
            } while (!empty($slug) && !empty($next));

            return new AdvertsPath($region, $category);
        });
    }
}
