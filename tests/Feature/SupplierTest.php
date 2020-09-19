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

    /**
     * Create a new object and try to update it in database
     */
    public function testUpdate()
    {
        // Create a new Object in the database
        $supplier = Supplier::factory()->create();
        $newName = 'Modified Supplier name';

        $response = $this->putJson(
            '/api/suppliers/' . $supplier->id,
            [ 'name' => $newName ]
        );

        // Check if response contains updated values
        $response
            ->assertStatus(200)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('supplier.name', $newName);
        
        // Check if updated object is in database
        $supplier->name = $newName;     
        $this->checkObject($supplier, '/api/suppliers/', 'supplier', 'name');
    }

    /**
     * Try to create an object with an existing unique attribute
     * 
     * @return void
     */
    public function testUniqueUpdate()
    {
        // Create two new objects in base
        $supplier1 = Supplier::factory()->create();
        $this->checkObject($supplier1, '/api/suppliers/', 'supplier', 'name');

        $supplier2 = Supplier::factory()->create();
        $this->checkObject($supplier2, '/api/suppliers/', 'supplier', 'name');

        // Try to set the second object name on the first one
        $response = $this->putJson(
            '/api/suppliers/' . $supplier1->id,
            [ 'name' => $supplier2->name ]
        );

        // Check the response of the API
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Create a new object and try to delete it
     */
    public function testDelete()
    {        
        // Create a new Object in the database
        $supplier = Supplier::factory()->create();

        // Check if the new object is in the database
        $this->checkObject($supplier, '/api/suppliers/', 'supplier', 'name');

        $response = $this->deleteJson(
            '/api/suppliers/' . $supplier->id
        );
       
        // Try to delete object
        $response
            ->assertStatus(200)
            ->assertJson([ 'success' => true ]);

        // Check if the object is not the detabase
        $this->checkObject($supplier, '/api/suppliers/', 'supplier', 'name', false);
    }
}
