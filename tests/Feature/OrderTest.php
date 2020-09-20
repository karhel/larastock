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

        // Make a new object with de Factory
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

    /**
     * Try to create an object with an existing unique attribute
     * 
     * @return void
     */
    public function testUniqueStore()
    {
        Supplier::factory()->count(5)->create();

        // Create a new object with de Factory
        $order = Order::factory()->create();

        // Make a new Supplier object with de Factory
        $newOrder = Supplier::factory()->make([
            'number' => $order->number,
            'supplier_id' => $order->supplier_id
        ]);

        $response = $this->postJson(
            '/api/orders',
            [ 
                'number' => $newOrder->number,
                'supplier_id' => $newOrder->supplier_id
            ]
        );

        // Check the response of the API
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['number']);
    }

    /**
     * Create a new object and try to update it in database
     */
    public function testUpdate()
    {
        Supplier::factory()->count(5)->create();

        // Create a new Object in the database
        $order = Order::factory()->create();
        $newNumber = 'MODIF-0000001';

        $response = $this->putJson(
            '/api/orders/' . $order->id,
            [ 
                'number' => $newNumber,
                'supplier_id' => $order->supplier_id 
            ]
        );

        // Check if response contains updated values
        $response
            ->assertStatus(200)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('order.number', $newNumber);
        
        // Check if updated object is in database
        $order->number = $newNumber;     
        $this->checkObject($order, '/api/orders/', 'order', 'number');
    }    

    /**
     * Create a new object and try to update it in database
     */
    public function testUniqueUpdate()
    {
        Supplier::factory()->count(5)->create();

        
        // Create two new objects in base
        $order1 = Order::factory()->create();
        $this->checkObject($order1, '/api/orders/', 'order', 'id');

        $order2 = ORder::factory()->create();
        $this->checkObject($order2, '/api/orders/', 'order', 'id');

        // Try to set object 2 attributes value to the first one
        $response = $this->putJson(
            '/api/orders/' . $order1->id,
            [ 
                'number' => $order2->number,
                'supplier_id' => $order2->supplier_id
            ]
        );

        // Check the response of the API
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['number']);
    }
}
