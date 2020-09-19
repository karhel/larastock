<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Model;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;    

    protected function checkObject(Model $model, $uri, $jsonWrap, $attribute, $assertTrue = true)
    {
        $response = $this->getJson(
            $uri . $model->id
        );

        if($assertTrue) {

            $response
                ->assertStatus(200)
                ->assertJsonPath("$jsonWrap.$attribute", $model->$attribute);
        }
        else {

            $response->assertStatus(404);
        }
    }

    /**
     * Disable FOREIGN CHECK to allow resetting database after each test
     */
    public function tearDown() : void
    {      
        $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '". env('DB_DATABASE') ."';";

        \DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
        $tables = \DB::select($sql);

        array_walk($tables, function($table){
            if ($table->TABLE_NAME != 'migrations') {
                \DB::table($table->TABLE_NAME)->truncate();
            }
        });
        
        \DB::statement("SET FOREIGN_KEY_CHECKS = 1;");
        parent::tearDown();
    }
}
