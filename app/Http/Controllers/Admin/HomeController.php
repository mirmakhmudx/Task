<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elastic\Elasticsearch\Client;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly Client $client
    ) {}

    public function index(): View
    {
        $indexParams = ['index' => 'adverts'];

        if (!$this->client->indices()->exists($indexParams)->getBool()) {
            $this->client->indices()->create([
                'index' => 'adverts',
                'body'  => [
                    'settings' => [
                        'number_of_shards'   => 1,
                        'number_of_replicas' => 0,
                    ],
                    'mappings' => [
                        'properties' => [
                            'id'     => ['type' => 'integer'],
                            'title'  => ['type' => 'text', 'analyzer' => 'standard'],
                            'price'  => ['type' => 'integer'],
                            'status' => ['type' => 'keyword'],
                        ],
                    ],
                ],
            ]);
        }

        return view('admin.home.index', ['client' => $this->client]);
    }
}
