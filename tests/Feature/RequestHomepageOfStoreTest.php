<?php

namespace Tests\Feature;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequestHomepageOfStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_not_successfull_response_for_not_configured_store_homepage(): void
    {
        Store::factory()->create(['domain'=>'different-domain.com']);

        $response = $this->get('https://wrong-domain.com/');

        $response->assertStatus(404);
    }

    public function test_returns_not_successfull_response_if_using_default_host_for_not_configured_store_homepage(): void
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }

    public function test_returns_successfull_response_for_configured_store_homepage(): void
    {
        $store = Store::factory()->create(['domain'=>'correct-domain.com']);

        $this->assertCount(1, Store::all());        
        $this->assertEquals('correct-domain.com', $store->fresh()->domain);

        $response = $this->withoutExceptionHandling()
                         ->get('https://correct-domain.com/');
        $response->assertStatus(200);
    }
}
