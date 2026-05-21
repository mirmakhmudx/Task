<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Entity\Adverts\Advert;
use App\Http\Requests\Adverts\SearchRequest;
use Elastic\Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Expression;

class SearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(?Category $category, ?Region $region, SearchRequest $request, int $perPage, int $page): LengthAwarePaginator
    {
        // 1. So'rovdan kelayotgan va ichi bo'sh bo'lmagan atributlarni filtrlab olamiz
        $values = array_filter((array)$request->input('attrs'), function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });

        // 2. Elasticsearch-ga yuboriladigan so'rov
        $response = $this->client->search([
            'index' => 'app',
            'body' => [
                '_source' => ['id'],
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'sort' => !empty($request['text']) ? [] : [
                    ['published_at' => ['order' => 'desc']],
                ],
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            [
                                ['term' => ['status' => Advert::STATUS_ACTIVE]],
                            ],
                            array_filter([
                                $category ? ['term' => ['categories' => $category->id]] : false,
                                $region   ? ['term' => ['regions'    => $region->id]]   : false,
                                !empty($request['text']) ? [
                                    'multi_match' => [
                                        'query'  => $request['text'],
                                        'fields' => ['title^3', 'content'],
                                    ]
                                ] : false,
                            ]),
                            array_map(function ($value, $id) {
                                return [
                                    'nested' => [
                                        'path'  => 'values',
                                        'query' => [
                                            'bool' => [
                                                'must' => array_values(array_filter([
                                                    ['match' => ['values.attribute' => $id]],
                                                    !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                    !empty($value['from'])   ? ['range' => ['values.value_int' => ['gte' => $value['from']]]] : false,
                                                    !empty($value['to'])     ? ['range' => ['values.value_int' => ['lte' => $value['to']]]]  : false,
                                                ])),
                                            ],
                                        ],
                                    ],
                                ];
                            }, $values, array_keys($values))
                        ),
                    ],
                ],
            ],
        ]);

        // 3. Elastic v8 — asResponse() ishlatmasak array kabi ishlaydi
        $hits = $response['hits']['hits'] ?? [];
        $ids  = array_column($hits, '_id');

        if (empty($ids)) {
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }

        // 4. ID-lar bo'yicha bazadan e'lonlarni yuklaymiz
        $items = Advert::active()
            ->with(['category', 'region', 'photos'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn($a) => array_search($a->id, $ids))
            ->values();

        // Elastic v8 da total value manba: $response['hits']['total']['value']
        $total = $response['hits']['total']['value'] ?? $response['hits']['total'] ?? count($ids);

        return new LengthAwarePaginator($items, $total, $perPage, $page);
    }
}
