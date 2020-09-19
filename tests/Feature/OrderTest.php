<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Supplier;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a collection of ($count) objects and
     * test if they are present in the database.
     * 
     * @return void
     */
    public function testIndex()
    {
        Supplier::factory()->count(5)->create();

        // Create $count objects in database with the Factory of the object class.
        $count = 100;
        Order::factory()->count($count)->create();
        
        $response = $this->getJson(
            '/api/orders'
        );

        // Check if there are 10 elements in the JSON response.
        $response
            ->assertStatus(200)
            ->assertJsonCount($count, 'orders');
    }
}
