<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Adverts\Region;
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

    public function search(
        ?Category $category,
        ?Region $region,
        SearchRequest $request,
        int $perPage,
        int $page
    ): LengthAwarePaginator {

        // 1. Faqat qiymatga ega bo'lgan (bo'sh bo'lmagan) filtrlarni saralab olamiz
        $values = array_filter((array)$request->input('attrs'), function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });

        // 2. Elasticsearch so'rovi (Body) karkasini quramiz
        $body = [
            '_source' => ['id'], // Bizga faqat hujjat ID-lari kifoya
            'from' => ($page - 1) * $perPage,
            'size' => $perPage,
            'sort' => !empty($request->get('text')) ? [] : [
                ['published_at' => ['order' => 'desc']]
            ],
            'query' => [
                'bool' => [
                    'must' => array_merge(
                        [
                            ['term' => ['status' => Advert::STATUS_ACTIVE]],
                        ],
                        // Kategoriya va Region filtrlarini array_filter orqali qo'shamiz
                        array_filter([
                            $category ? ['term' => ['categories' => $category->id]] : false,
                            $region ? ['term' => ['regions' => $region->id]] : false,
                            !empty($request->get('text')) ? [
                                'multi_match' => [
                                    'query' => $request->get('text'),
                                    'fields' => ['title^3', 'content'] // Sarlavha 3 barobar muhimroq
                                ]
                            ] : false,
                        ]),
                        // 3. Dinamik atributlarni (Nested) xaritaga solib (array_map) must ichiga qo'shamiz
                        array_map(function ($value, $id) {
                            return [
                                'nested' => [
                                    'path' => 'values',
                                    'query' => [
                                        'bool' => [
                                            'must' => array_values(array_filter([
                                                ['match' => ['values.attribute' => $id]],
                                                !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                !empty($value['from']) ? ['range' => ['values.value_int' => ['gte' => (int)$value['from']]]] : false,
                                                !empty($value['to']) ? ['range' => ['values.value_int' => ['lte' => (int)$value['to']]]] : false,
                                            ]))
                                        ]
                                    ]
                                ]
                            ];
                        }, $values, array_keys($values))
                    )
                ]
            ]
        ];

        // Elasticsearch-ga qidiruv so'rovini yuboramiz
        $response = $this->client->search([
            'index' => 'app',
            'type'  => 'adverts',
            'body'  => $body,
        ]);

        // Topilgan ID-larni yig'ib olamiz
        $ids = array_column($response['hits']['hits'], '_id');

        if (empty($ids)) {
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }

        // 4. Bazadan ma'lumotlarni chiqarib olish (Reyting ketma-ketligini saqlagan holda)
        $items = Advert::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderBy(new Expression('FIELD(id, ' . implode(',', $ids) . ')'))
            ->get();

        // Jami topilgan natijalar miqdori
        $total = $response['hits']['total']['value'] ?? $response['hits']['total'];

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}
