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
}
