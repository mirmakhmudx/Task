<?php

namespace App\Console\Commands\Advert;

use App\Entity\Adverts\Advert;
use App\UseCases\Adverts\AdvertService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    protected $signature   = 'advert:expire';
    protected $description = 'Close expired adverts';

    public function __construct(
        private readonly AdvertService $service
    ) {
        parent::__construct();
    }

    public function handle(): bool
    {
        $success = true;

        Advert::active()
            ->where('expires_at', '<', Carbon::now())
            ->cursor()
            ->each(function (Advert $advert) use (&$success) {
                try {
                    $this->service->expire($advert);
                } catch (\DomainException $e) {
                    $this->error($e->getMessage());
                    $success = false;
                }
            });

        $this->info('Done.');
        return $success;
    }
}
