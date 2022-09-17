<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Sellenvo\ShopifyApiClient\ApiClient;
use Sellenvo\ShopifyApiClient\Product;

final class ProductTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        $this->apiClient = new ApiClient(env('STORE_NAME'), env('API_KEY'), env('ACCESS_TOKEN'));
    }

    /** @test */
    public function test_it_can_import_products_from_csv_file()
    {

        $product = new Product($this->apiClient);

        $products = $product->importFromCsv(__DIR__ . '/products.csv');

        $this->assertCount(4, $products);
    }


    /** @test */
    public function test_it_can_read_products_from_csv_file()
    {

        $product = new Product($this->apiClient);

        $products = $product->readFromCsvFile(__DIR__ . '/products.csv');

        $this->assertCount(4, $products);
    }

    /** @test */
    public function test_it_should_format_the_csv_data()
    {

        $product = new Product($this->apiClient);

        $products = $product->readFromCsvFile(__DIR__ . '/products.csv');

        $firstProduct = $products[0]['product'];

        $expectedOptions = [
            ['name' => 'Title'],
        ];

        $expectedVariants = [
            [
                'sku' => 'AV-30460',
                'grams' => 0,
                'inventory_quantity' => 0,
                'inventory_policy' => 'deny',
                'fulfillment_service' => 'manual',
                'price' => 12.99,
                'compare_at_price' => null,
                'requires_shipping' => true,
                'taxable' => true,
                'barcode' => null,
                'option1' => 'Default Title',
                'option2' => null,
                'option3' => null,
            ]
        ];

        $this->assertEquals('handle value', $firstProduct['handle']);

        $this->assertEquals('title value', $firstProduct['title']);

        $this->assertEquals('body_html value', $firstProduct['body_html']);

        $this->assertEmpty($firstProduct['product_type']);
        $this->assertEmpty($firstProduct['tags']);
        $this->assertNull($firstProduct['published_at']);
        $this->assertEquals($expectedOptions, $firstProduct['options']);
        $this->assertEquals($expectedVariants, $firstProduct['variants']);

    }


    /** @test */
    public function test_it_can_create_product_and_post_it_to_the_api()
    {

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

        $product = new Product($this->apiClient);
        $response = $product->create($productData);

        $this->assertIsArray($response);
        $this->assertNotEmpty($response['product']);
        $this->assertNotEmpty($response['product']['id']);
    }

    /** @test */
    public function it_can_retrieve_products_list_from_api()
    {
        $product = new Product($this->apiClient);

        $list = $product->getProductsList();

        $this->assertIsArray($list);
    }

    /** @test */
    public function clearEmptyKeys_should_unset_any_empty_key_in_the_product_list()
    {
        $productData = [
            'handle' => 'handle value',
            'title' => 'title value',
            'body_html' => 'body_html value',
            'vendor' => '-',
            'product_type' => '',
            'tags' => 'N/A',
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
        ];

        $product = new Product($this->apiClient);

        $updatedData = $product->clearEmptyKeys($productData);

        $this->assertArrayNotHasKey('vendor', $updatedData);
        $this->assertArrayNotHasKey('product_type', $updatedData);
        $this->assertArrayNotHasKey('tags', $updatedData);

    }

    /** @test */
    public function clearEmptyKeys_should_add_nullable_to_the_product_title_if_there_is_any_empty_key()
    {
        $productData = [
            'handle' => 'handle value',
            'title' => 'title value',
            'body_html' => 'body_html value',
            'vendor' => '-',
            'product_type' => '',
            'tags' => 'N/A',
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
        ];

        $product = new Product($this->apiClient);

        $updatedData = $product->clearEmptyKeys($productData);

        $this->assertEquals('title value nullable', $updatedData['title']);


    }

    /** @test */
    public function test_it_can_update_product()
    {

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
                        'inventory_quantity' => 0,
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

        $product = new Product($this->apiClient);
        $response = $product->create($productData);

        $id = $response['product']['id'];
        $variant = ['inventory_quantity' => 50];
        $productData['product']['variants'][0] = $variant;

        $response = $product->update($id, $productData);

        $this->assertEquals(50, $response['product']['variants'][0]['inventory_quantity']);

    }

    /** @test */
    public function test_()
    {
        $product = new Product($this->apiClient);
        $product->updateAllProductQuantities(50);
    }


}