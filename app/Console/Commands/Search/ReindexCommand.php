<?php

namespace App\Console\Commands\Search;

use App\Services\Search\AdvertIndexer;
use Illuminate\Console\Command;
use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Value;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';
    protected $description = 'Reindex barcha aktiv e\'lonlarni Elasticsearch-ga qayta yuklash';

    private $indexer;

    public function __construct(AdvertIndexer $indexer)
    {
        parent::__construct();
        $this->indexer = $indexer;
    }

    public function handle(): bool
    {
        $this->info('Elasticsearch indekslari tozalanmoqda...');
        $this->indexer->clear(); // Videodagi $this->indexer->clear()

        $this->info('Bazadan qayta indekslash boshlandi...');

        $query = Advert::active()->with(['category', 'region', 'values'])->orderBy('id');

        if ($query->count() === 0) {
            $this->warn('Yuklash uchun aktiv e\'lonlar topilmadi.');
            return true;
        }

        foreach ($query->cursor() as $advert) {
            $this->indexer->index($advert);
        }

        $this->info('Reindex muvaffaqiyatli yakunlandi!');
        return true;
    }
}
