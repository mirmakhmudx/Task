<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Requests\Adverts\SearchRequest;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    private $client;

    public function __construct()
    {
        // Docker muhitidagi ES portiga ulanish
        $hosts = explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'));
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function search(
        ?Category $category,
        ?Region $region,
        SearchRequest $request,
        int $perPage,
        int $page
    ): LengthAwarePaginator {

        // Hozircha muallif ES so'rovlar matnini (body) bo'sh qoldirgan, keyingi daqiqalarda to'ldiradi
        $response = $this->client->search([
            'index' => 'app',
            'body'  => [
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'query' => [
                    'match_all' => (object)[] // Vaqtincha hamma narsani chiqarib turish uchun
                ]
            ],
        ]);

        // ES dan qaytgan natija ichidan faqat hujjatlarning ID-larini ajratib olamiz
        $ids = array_column($response['hits']['hits'], '_id');

        if (empty($ids)) {
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }

        // Bazadan ID-lar bo'yicha e'lonlarni to'g'ri tartibda yuklab olish
        $items = Advert::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')') // ES bergan reyting tartibini saqlash
            ->get();

        // Jami topilgan natijalar soni
        $total = $response['hits']['total']['value'] ?? $response['hits']['total'];

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}
