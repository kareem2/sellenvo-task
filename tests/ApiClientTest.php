<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Sellenvo\ShopifyApiClient\ApiClient;

final class ApiClientTest extends TestCase
{
    /** @test */
    public function test_it_can_get_products_list_from_the_api()
    {
        $apiClient = new ApiClient();

        $this->assertIsArray($apiClient->get('products'));
    }


}