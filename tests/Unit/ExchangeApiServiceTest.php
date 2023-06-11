<?php

namespace Tests\Feature;

use App\Services\Api\ExchangeApiService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Tests\TestCase;

class ExchangeApiServiceTest extends TestCase
{
    private $expectedResponse = [
        'result' => [
            'EUR' => 0.85,
        ],
    ];

    public function testGetCurrencyRate()
    {
        $baseCurrency = 'USD';
        $targetCurrency = 'EUR';


        Http::fake([
            'https://api.fastforex.io/*' => Http::response($this->expectedResponse, 200),
        ]);

        $this->assertEquals(
            (new ExchangeApiService())->getCurrencyRate($baseCurrency, $targetCurrency),
            [
                'data' => $this->expectedResponse,
                'status' => 200
            ]);
    }
}
