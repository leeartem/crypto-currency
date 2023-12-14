<?php

namespace App\Services\Api\Currency;

use App\Exceptions\Api\Exceptions\IncorrectParamsException;
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
     * @throws IncorrectParamsException|GuzzleException
     */
    public function run(array $params): array
    {
        $this->isValid($params);

        $rates =  $this->blockchainInfoClient->getRates();
        $currency = $params['currency'] ?? null;

        if ($currency && isset($rates[$currency])) {
            return [
                $currency => $rates[$currency]['buy']
            ];
        }

        $result = [];
        foreach ($rates as $rate) {
            $result[$rate['symbol']] = $rate['buy'];
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
}
