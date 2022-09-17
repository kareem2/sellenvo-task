<?php

namespace Sellenvo\ShopifyApiClient;

class Product
{

    private $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function importFromCsv($filePath)
    {
        $products = $this->readFromCsvFile($filePath);
        $response = [];
        foreach ($products as $product){
            $response[] = $this->create($product);
        }

        return $response;

    }

    public function readFromCsvFile($filePath)
    {
        $file = fopen($filePath, 'r');

        $productsList = [];
        $header = fgetcsv($file);

        while (($data = fgetcsv($file)) !== false){
            $productsList[] = $data;
        }

        fclose($file);

        return $this->formatProducts($productsList);
    }

    private function formatProducts($productsList)
    {
        $list = [];

        foreach ($productsList as $product){
            $publishedAt = date('Y-m-d H:i:s');
            if($product[6] == 'FALSE')
                $publishedAt = null;

            $options = [];

            if(!empty($product[7]))
                $options[] = ['name' => $product[7]];

            if(!empty($product[9]))
                $options[] = ['name' => $product[9]];

            if(!empty($product[11]))
                $options[] = ['name' => $product[11]];


            $variants = [
                [
                    'sku' => $product[13],
                    'grams' => $product[14],
                    'inventory_quantity' => $product[16],
                    'inventory_policy' => $product[17],
                    'fulfillment_service' => $product[18],
                    'price' => $product[19],
                    'compare_at_price' => $product[20],
                    'requires_shipping' => $product[21] == 'TRUE',
                    'taxable' => $product[22] == 'TRUE',
                    'barcode' => $product[23],
                    'option1' => $product[8],
                    'option2' => $product[10],
                    'option3' => $product[12],

                ]
            ];

            $list[] = [
                'product' => [
                    'handle' => $product[0],
                    'title' => $product[1],
                    'body_html' => $product[2],
                    'vendor' => $product[3],
                    'product_type' => $product[4],
                    'tags' => $product[5],
                    'published_at' => $publishedAt,
                    'options' => $options,
                    'variants' => $variants,
                ]
            ];
        }

        return $list;
    }

    public function create($productData)
    {
        return $this->apiClient->create('products', $productData);
    }

    public function getProductsList()
    {
        $list = $this->apiClient->get('products');

        foreach ($list['products'] as $key => $product){
            $list['products'][$key] = $this->clearEmptyKeys($product);
        }

        return $list;
    }

    public function clearEmptyKeys(array $productData)
    {
        $isNullable = false;
        foreach ($productData as $key => $attribute) {

            if($attribute == '-' || $attribute == 'N/A' || empty($attribute)){
                $isNullable = true;
                unset($productData[$key]);
            }
        }

        if($isNullable)
            $productData['title'] = $productData['title'] . ' nullable';

        return $productData;
    }

    public function update($id, $productData)
    {
        return $this->apiClient->put("products/{$id}", $productData);
    }

    public function updateAllProductQuantities($quantity)
    {
        $products = $this->getProductsList();

        foreach ($products['products'] as $product){
            $data = [
                'product' => [
                    'variants' => [
                        [
                            'inventory_quantity' => $quantity
                        ]
                    ]
                ]
            ];

            $this->update($product['id'], $data);
        }

    }
}