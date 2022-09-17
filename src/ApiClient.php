<?php

namespace Sellenvo\ShopifyApiClient;


use GuzzleHttp\Client;

class ApiClient
{

    private $storeName;
    private $apiKey;
    private $accessToken;

    public function __construct($storeName, $apiKey, $accessToken)
    {
        $this->storeName = $storeName;
        $this->apiKey = $apiKey;
        $this->accessToken = $accessToken;
    }

    public function get($endpoint)
    {
        $client = new Client();
        $result = $client->request('GET', "https://{$this->apiKey}:{$this->accessToken}@{$this->storeName}.myshopify.com/admin/{$endpoint}.json");

        return json_decode($result->getBody(), true);

    }

    public function create($endpoint, $data)
    {
        $client = new Client();
        $result = $client->request('POST', "https://{$this->apiKey}:{$this->accessToken}@{$this->storeName}.myshopify.com/admin/{$endpoint}.json", [
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        return json_decode($result->getBody(), true);

    }

    public function put($endpoint, $data)
    {
        $client = new Client();
        $result = $client->request('PUT', "https://{$this->apiKey}:{$this->accessToken}@{$this->storeName}.myshopify.com/admin/{$endpoint}.json", [
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        return json_decode($result->getBody(), true);
    }
}