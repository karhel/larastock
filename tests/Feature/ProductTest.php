<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
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
        // Create $count Product objects in database with the Factory of the object class.
        $count = 10;
        Product::factory()->count($count)->create();
        
        $response = $this->getJson(
            '/api/products'
        );

        // Check if there are 10 elements in the JSON response.
        $response
            ->assertStatus(200)
            ->assertJsonCount($count, 'products');
    }

    /**
     * Make a object and try to store it in the database
     * with de API.
     * 
     * @return void
     */
    public function testStore()
    {
        // Make a new Product object with de Factory
        $product = Product::factory()->make();

        $response = $this->postJson(
            '/api/products',
            [ 'name' => $product->name ]
        );

        // Check the response of the API
        $response
            ->assertStatus(201)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('product.name', $product->name);

        // Check if the object exists in the database
        $product->id = $response->decodeResponseJson()['product']['id'];
        //$this->checkProduct($product);
        $this->checkObject($product, '/api/products/', 'product', 'name');
    }

    /**
     * Create a new object and try to update it in database
     */
    public function testUpdate()
    {
        // Create a new Object in the database
        $product = Product::factory()->create();
        $newName = 'Modified Product name';

        $response = $this->putJson(
            '/api/products/' . $product->id,
            [ 'name' => $newName ]
        );

        // Check if response contains updated values
        $response
            ->assertStatus(200)
            ->assertJson([ 'success' => true ])
            ->assertJsonPath('product.name', $newName);
        
        // Check if updated object is in database
        $product->name = $newName;     
        //$this->checkProduct($product);
        $this->checkObject($product, '/api/products/', 'product', 'name');
    }

    /**
     * Create a new object and try to delete it
     */
    public function testDelete()
    {        
        // Create a new Object in database
        $product = Product::factory()->create();

        // Check if the new object is in the database
        //$this->checkProduct($product);
        $this->checkObject($product, '/api/products/', 'product', 'name');

        $response = $this->deleteJson(
            '/api/products/' . $product->id
        );
       
        // Try to delete object
        $response
            ->assertStatus(200)
            ->assertJson([ 'success' => true ]);

        // Check if the object is not the detabase
        //$this->checkProduct($product, false);
        $this->checkObject($product, '/api/products/', 'product', 'name', false);
    }
}
