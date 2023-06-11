<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyConverterControllerTest extends TestCase
{
    public function testValidCurrencyConverter()
    {
        $response = $this->post('/api/convert', [
            'amount' => 100,
            'baseCurrency' => 'USD',
            'targetCurrency' => 'EUR',
        ]);

        $response->assertStatus(200);
    }

    public function testInvalidCurrencyConverter()
    {
        $response = $this->post('/api/convert', [
            'amount' => 100,
            'baseCurrency' => 'USD',
            'targetCurrency' => 1111,
        ]);

        $response->assertStatus(302);
    }

    public function testCurrencyConverter()
    {
        Http::fake([
            'https://api.fastforex.io/*' => Http::response([
                'result' => [
                    'EUR' => 0.85,
                ],
            ], 200),
        ]);

        $response = $this->post('/api/convert', [
            'amount' => 100,
            'baseCurrency' => 'USD',
            'targetCurrency' => 'EUR',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => 85,
                'status' => 200,
            ]);
    }
}
