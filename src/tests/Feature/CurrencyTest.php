<?php

namespace Tests\Feature;

use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function testRates()
    {
        $response = $this->get(
            route('api.v1.call') . '?method=rates',
            [
                'Authorization' => 'Bearer ' . config('auth.token')
            ]
        );

        $content = $response->json();
        $this->assertEquals('success', $content['status']);
        $this->assertEquals(200, $content['code']);
        $this->assertCount(28, $content['data']);
    }

    public function testRatesWithCurrency()
    {
        $response = $this->get(
            route('api.v1.call') . '?method=rates&currency=USD',
            [
                'Authorization' => 'Bearer ' . config('auth.token')
            ]
        );

        $content = $response->json();
        $this->assertEquals('success', $content['status']);
        $this->assertEquals(200, $content['code']);
        $this->assertCount(1, $content['data']);
    }

    public function testConvert()
    {
        $url =route('api.v1.call') . '?method=convert&currency_from=USD&currency_to=BTC&value=43000';
        $response = $this->get(
            $url,
            [
                'Authorization' => 'Bearer ' . config('auth.token')
            ]
        );

        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $params);

        $content = $response->json();
        $this->assertEquals('success', $content['status']);
        $this->assertEquals(200, $content['code']);
        $this->assertCount(5, $content['data']);

        $data = $content['data'];
        $this->assertEquals($params['currency_from'], $data['currency_from']);
        $this->assertEquals($params['currency_to'], $data['currency_to']);
        $this->assertEquals($params['value'], $data['value']);
        $this->assertTrue(isset($data['converted_value']));
        $this->assertTrue(isset($data['rate']));
    }
}
