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

    /**
     * Make a object and try to store it in the database
     * with de API.
     * 
     * @return void
     */
    public function testStore()
    {
        Supplier::factory()->count(5)->create();

        // Make a new Product object with de Factory
        $order = Order::factory()->make();

        $response = $this->postJson(
            '/api/orders',
            [ 
                'number' => $order->number,
                'supplier_id' => $order->supplier_id
            ]
        );

        // Check the response of the API
        $response
            ->assertStatus(201)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('order.number', $order->number);

        // Check if the object exists in the database
        $order->id = $response->decodeResponseJson()['order']['id'];
        $this->checkObject($order, '/api/orders/', 'order', 'number');
    }
}
