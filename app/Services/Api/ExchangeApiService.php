<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExchangeApiService
{
    public function __construct()
    {
        $this->uri = config('services.exchange_uri');
        $this->key = config('services.exchange_key');
    }

    /**
     * Get currency rate from external API
     *
     * @param $base
     * @param $target
     * @return array|mixed|null
     */
    public function getCurrencyRate($base, $target): mixed
    {
        try {
            return $this->response(
                Http::get($this->uri . 'fetch-one?api_key=' . $this->key . '&from=' . $base . '&to=' . $target)
            );
        } catch (HttpException $e) {
            return [
                'data' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    /**
     * Validate the external API's response
     *
     * @param $stream
     * @return array
     */
    public function response($stream): array
    {
        try {
            return [
                'data' => json_decode($stream->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR),
                'status' => $stream->getStatusCode()
            ];
        } catch (\JsonException $e) {
            return [
                'data' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}
