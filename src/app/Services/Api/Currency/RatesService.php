<?php

namespace App\Services\Api\Currency;

use App\Exceptions\Api\Exceptions\IncorrectParamsException;
use App\Exceptions\Currency\InvalidCurrencyException;
use App\Services\Api\AbstractApiService;
use App\Services\Api\Currency\Vendors\BlockchainInfo\BlockchainInfoClient;
use App\Services\Api\Currency\Vendors\BlockchainInfo\Exceptions\ApiBadResponse;
use GuzzleHttp\Exception\GuzzleException;

class RatesService extends AbstractApiService
{
    public function __construct(
        private BlockchainInfoClient $blockchainInfoClient
    ){
    }

    /**
     * @param array $params
     * @return array
     * @throws ApiBadResponse
     * @throws IncorrectParamsException|GuzzleException|InvalidCurrencyException
     */
    public function run(array $params): array
    {
        $this->isValid($params);

        $rates =  $this->blockchainInfoClient->getRates();
        $currency = $params['currency'] ?? null;

        if ($currency) {
            if (!isset($rates[$currency])) {
                throw new InvalidCurrencyException();
            }

            return [
                $currency => $this->handleValue($rates[$currency])
            ];
        }

        $result = [];
        foreach ($rates as $key => $rate) {
            $result[$key] = $this->handleValue($rate);
        }

        asort($result);

        return $result;
    }

    public function getRules(): array
    {
        return [
            'currency' => 'nullable|string|min:3|max:3'
        ];
    }

    private function handleValue(float $value): float
    {
        return round($value * config('currency.commission_multiplier'), 2);
    }
}
