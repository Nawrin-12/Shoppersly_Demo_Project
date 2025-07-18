<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_list_api_works()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }
}
