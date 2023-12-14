<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testAuthWithNoToken()
    {
        $response = $this->get(
            route('api.v1.call')
        );

        $response->assertJson(
            [
                'status' => 'error',
                'code' => 403,
                'error' => 'Invalid Token',
            ]
        );
    }

    public function testAuthWithToken()
    {
        $response = $this->get(
            route('api.v1.call') . '?method=rates',
            [
                'Authorization' => 'Bearer ' . config('auth.token')
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAuthWithInvalidToken()
    {
        $response = $this->get(
            route('api.v1.call') . '?method=rates',
            [
                'Authorization' => 'Bearer 123'
            ]
        );

        $response->assertJson(
            [
                'status' => 'error',
                'code' => 403,
                'error' => 'Invalid Token',
            ]
        );
    }
}
