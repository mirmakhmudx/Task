<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Value;

class InitCommand extends Command
{
    protected $signature = 'search:init';
    protected $description = 'Elasticsearch-da "app" indeksini darslik bo\'yicha to\'liq sozlash va reindex qilish';

    private $client;

    public function __construct()
    {
        parent::__construct();

        $hosts = explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'));
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function handle(): bool
    {
        $indexName = 'app';

        // 1. Eski indeksni o'chirish
        try {
            $this->client->indices()->delete(['index' => $indexName]);
            $this->info("Eski '{$indexName}' indeksi o'chirildi.");
        } catch (ClientResponseException $exception) {
            if ($exception->getResponse()->getStatusCode() === 404) {
                $this->line("Eski indeks topilmadi, yangisini yaratishga o'tamiz...");
            }
        }

        // 2. Indeks sozlamalari (Settings va Mappings) - Rasmdagi aniq variant
        $this->client->indices()->create([
            'index' => $indexName,
            'body'  => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,

                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '& => and'
                                ]
                            ]
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ]
                        ],
                        'analyzer' => [
                            'default' => [ // Muallif yozganidek butun indeks uchun default tahlilchi
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace'
                                ],
                                'tokenizer' => 'whitespace', // Rasmdagi aniq tokenizer
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter'
                                ]
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' => [
                        'id'           => ['type' => 'integer'],
                        'published_at' => ['type' => 'date', 'format' => 'strict_date_optional_time||epoch_millis'],
                        'title'        => ['type' => 'text'], // Analyzer yozish shartmas, default o'zi ulanadi
                        'content'      => ['type' => 'text'], // Analyzer yozish shartmas, default o'zi ulanadi
                        'price'        => ['type' => 'integer'],
                        'status'       => ['type' => 'keyword'],
                        'categories'   => ['type' => 'integer'],
                        'regions'      => ['type' => 'integer'],
                        'values'       => [
                            'type' => 'nested',
                            'properties' => [
                                'attribute'    => ['type' => 'integer'],
                                'value_string' => ['type' => 'keyword'],
                                'value_int'    => ['type' => 'integer'],
                            ]
                        ],
                    ]
                ]
            ]
        ]);
        $this->info("Yangi '{$indexName}' indeksi tahlilchilari bilan muvaffaqiyatli yaratildi.");

        // 3. Ma'lumotlarni qayta indekslash (Reindex)
        $this->info("Bazadan ma'lumotlarni yuklash boshlandi...");

        $query = Advert::active()->with(['category', 'region', 'values'])->orderBy('id');

        if ($query->count() === 0) {
            $this->warn("Bazada yuklash uchun aktiv e'lonlar topilmadi.");
            return true;
        }

        foreach ($query->cursor() as $advert) {
            $regions = [];
            if ($region = $advert->region) {
                do {
                    $regions[] = (int) $region->id;
                } while ($region = $region->parent);
            }

            $values = array_map(function (Value $value) {
                return [
                    'attribute'    => (int) $value->attribute_id,
                    'value_string' => (string) $value->value,
                    'value_int'    => is_numeric($value->value) ? (int) $value->value : null,
                ];
            }, $advert->values()->getModels());

            $this->client->index([
                'index' => $indexName,
                'id'    => $advert->id,
                'body'  => [
                    'id'           => (int) $advert->id,
                    'published_at' => $advert->published_at ? $advert->published_at->toIso8601String() : null,
                    'title'        => $advert->title,
                    'content'      => $advert->content,
                    'price'        => (int) $advert->price,
                    'status'       => $advert->status,
                    'categories'   => array_merge(
                        [$advert->category->id],
                        $advert->category->ancestors()->pluck('id')->toArray()
                    ),
                    'regions'      => $regions,
                    'values'       => $values,
                ]
            ]);
        }
        $this->info("Muvaffaqiyatli bajarildi! Barcha e'lonlar darslik standarti bo'yicha indekslandi.");
        return true;
    }
}
