## How To Use

### Install Dependencies
`composer install`


### Create Api Client

#### Using env function
We can store the API store name, api key, and the access token in the `.env` file, and use the env function to read them
```
$apiClient = new ApiClient(env('STORE_NAME'), env('API_KEY'), env('ACCESS_TOKEN'));
```

### Working On Products
To work on products, first we need to initialize the product object
```
$apiClient = new ApiClient(env('STORE_NAME'), env('API_KEY'), env('ACCESS_TOKEN'));

$product = new Product($apiClient);
```

#### Get Products List
```
$list = $product->getProductsList();
```
#### Create Product
```
$productData = [
    'product' => [
        'handle' => 'handle value',
        'title' => 'title value',
        'body_html' => 'body_html value',
        'vendor' => 'A Vogel (BioForce)',
        'product_type' => '',
        'tags' => '',
        'published_at' => NULL,
        'options' => [
            [
                'name' => 'Title',
            ]
        ],
        'variants' => [
            [
                'sku' => 'AV-30460',
                'grams' => '0',
                'inventory_quantity' => '0',
                'inventory_policy' => 'deny',
                'fulfillment_service' => 'manual',
                'price' => '12.99',
                'compare_at_price' => '',
                'requires_shipping' => true,
                'taxable' => true,
                'barcode' => '',
                'option1' => 'Default Title',
                'option2' => '',
                'option3' => '',
            ],
        ]
    ]
];

$response = $product->create($productData);
```

#### Update Product
Example of a function that updates all products quantities
```
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

// function call
$product->updateAllProductQuantities(50);
```

#### Import from CSV file
The CSV file must follow the columns order like in the sample file in the test folder.
```
$product = new Product($this->apiClient);

$products = $product->importFromCsv(__DIR__ . '/products.csv');
```