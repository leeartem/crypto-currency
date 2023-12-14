<?php

namespace App\Services\Api;

use App\Exceptions\Api\Exceptions\IncorrectParamsException;
use App\Exceptions\Api\Exceptions\UndefinedMethodException;
use App\Exceptions\Currency\InvalidCurrencyException;
use App\Services\Api\Currency\ConvertService;
use App\Services\Api\Currency\RatesService;
use GuzzleHttp\Exception\GuzzleException;

class ApiMethodResolver
{
    /**
     * @param string $methodName
     * @param array $params
     * @return array
     * @throws Currency\Vendors\BlockchainInfo\Exceptions\ApiBadResponse
     * @throws IncorrectParamsException
     * @throws InvalidCurrencyException
     * @throws UndefinedMethodException
     * @throws GuzzleException
     */
    public function map(string $methodName, array $params): array
    {
        return match ($methodName) {
            'rates' => app(RatesService::class)->run($params),
            'convert' => app(ConvertService::class)->run($params),
            default => throw new UndefinedMethodException('Undefined Method', 409)
        };
    }
}
