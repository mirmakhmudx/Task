<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index()
    {
        $client = ClientBuilder::create()->setHosts(config('http://elasticsearch:9200'))->build();
        $indexParams = ['index' => 'adverts'];

        if (!$client->indices()->exists($indexParams)->getBool()) {
            $client->indices()->create([
                'index' => 'adverts',
                'body'  => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 0,
                    ],
                    'mappings' => [
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'text', 'analyzer' => 'standard'],
                            'price' => ['type' => 'integer'],
                            'status' => ['type' => 'keyword'],
                        ]
                    ]
                ]
            ]);
        }
        return view('admin.home.index', compact('client'));
    }
}
