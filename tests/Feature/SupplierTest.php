<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;

class SupplierTest extends TestCase
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
        // Create $count objects in database with the Factory of the object class.
        $count = 10;
        Supplier::factory()->count($count)->create();
        
        $response = $this->getJson(
            '/api/suppliers'
        );

        // Check if there are 10 elements in the JSON response.
        $response
            ->assertStatus(200)
            ->assertJsonCount($count, 'suppliers');
    }

    /**
     * Make a object and try to store it in the database
     * with de API.
     * 
     * @return void
     */
    public function testStore()
    {
        // Make a new Supplier object with de Factory
        $supplier = Supplier::factory()->make();

        $response = $this->postJson(
            '/api/suppliers',
            [ 'name' => $supplier->name ]
        );

        // Check the response of the API
        $response
            ->assertStatus(201)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('supplier.name', $supplier->name);

        // Check if the object exists in the database
        $supplier->id = $response->decodeResponseJson()['supplier']['id'];
        $this->checkObject($supplier, '/api/suppliers/', 'supplier', 'name');
    }

    /**
     * Try to create an object with an existing unique attribute
     * 
     * @return void
     */
    public function testUniqueStore()
    {
        // Create a new object
        $supplier = Supplier::factory()->create();
        
        // Make a new Supplier object with de Factory
        $newSupplier = Supplier::factory()->make([
            'name' => $supplier->name
        ]);

        $response = $this->postJson(
            '/api/suppliers',
            [ 'name' => $newSupplier->name ]
        );

        // Check the response of the API
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    
}
