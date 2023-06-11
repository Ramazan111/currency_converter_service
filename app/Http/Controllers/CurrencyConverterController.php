<?php

namespace App\Http\Controllers;

use App\Services\Api\ExchangeApiService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class CurrencyConverterController extends Controller
{
    public function __construct(
        public ExchangeApiService $exchangeApiService
    )
    {
    }

    /**
     * Convert currency
     * @OA\Post(
     *     path="/api/convert",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="baseCurrency", type="string"),
     *                 @OA\Property(property="targetCurrency", type="string"),
     *                 @OA\Property(property="amount", type="integer"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="An example endpoint")
     * )
     * @param Request $request
     * @return array
     */
    public function currencyConvert(Request $request): array
    {
        $payload = $request->validate([
            'baseCurrency' => 'string|required|max:10',
            'targetCurrency' => 'string|required|max:10',
            'amount' => 'numeric|required',
        ]);

        try {
            $currencyRate = $this->exchangeApiService->getCurrencyRate($payload['baseCurrency'], $payload['targetCurrency']);

            return [
                'status' => $currencyRate['status'],
                'data' => ($currencyRate['status'] == 200) ? $currencyRate['data']['result'][$payload['targetCurrency']] * $payload['amount'] : $currencyRate['data']['error']
            ];
        } catch (\Throwable $e) {
            return [
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "data" => $e->getMessage()
            ];
        }
    }
}
