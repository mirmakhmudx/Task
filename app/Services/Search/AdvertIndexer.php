<?php

namespace App\Services\Search;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Value;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;

class AdvertIndexer
{
    private $client;
    private $indexName = 'app';

    public function __construct()
    {
        $hosts = explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'));
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function clear(): void
    {
        try {
            $this->client->deleteByQuery([
                'index' => $this->indexName,
                'body' => [
                    'query' => [
                        'match_all' => (object)[]
                    ]
                ]
            ]);
        } catch (ClientResponseException $e) {
            if ($e->getResponse()->getStatusCode() !== 404) {
                throw $e;
            }
        }
    }

    // Bitta e'lonni indeksga qo'shish/yangilash
    public function index(Advert $advert): void
    {
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
            'index' => $this->indexName,
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

    public function deleteIndex(): void
    {
        try {
            $this->client->indices()->delete(['index' => $this->indexName]);
        } catch (ClientResponseException $e) {}
    }
}
