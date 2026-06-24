<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;

class InitCommand extends Command
{
    protected $signature = 'search:init';
    protected $description = 'Elasticsearch-da "app" indeksini sozlash (settings + mappings)';

    private $client;

    public function __construct()
    {
        parent::__construct();

        $hosts = explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'));
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function handle(): int
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

        // 2. Indeks sozlamalari (settings + mappings)
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
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace'
                                ],
                                'tokenizer' => 'whitespace',
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
                        'title'        => ['type' => 'text'],
                        'content'      => ['type' => 'text'],
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

        $this->info("Yangi '{$indexName}' indeksi muvaffaqiyatli yaratildi.");
        $this->line("Ma'lumotlarni indekslash uchun: php artisan search:reindex");

        return self::SUCCESS;
    }
}
