<?php

namespace App\Services\Api\Currency;

use App\Exceptions\Api\Exceptions\IncorrectParamsException;
use App\Exceptions\Currency\InvalidCurrencyException;
use App\Services\Api\AbstractApiService;
use App\Services\Api\Currency\Dto\CoinsEnum;
use App\Services\Api\Currency\Vendors\BlockchainInfo\BlockchainInfoClient;
use GuzzleHttp\Exception\GuzzleException;

class ConvertService extends AbstractApiService
{
    public function __construct(
        private BlockchainInfoClient $blockchainInfoClient
    ){
    }

    /**
     * @param array $params
     * @return array
     * @throws IncorrectParamsException
     * @throws InvalidCurrencyException
     * @throws Vendors\BlockchainInfo\Exceptions\ApiBadResponse
     * @throws GuzzleException
     */
    public function run(array $params): array
    {
        // вместо чисто валидации можно было бы собирать DTOхи с параметрами,
        // но обойдемся массивом
        $this->isValid($params);

        $rates = $this->blockchainInfoClient->getRates();

        [$result, $rate] = $this->convert(
            $params['currency_from'],
            $params['currency_to'],
            $params['value'],
            $rates
        );

        return [
            'currency_from' => $params['currency_from'],
            'currency_to' => $params['currency_to'],
            'value' => (float) $params['value'],
            'converted_value' => $result,
            'rate' => round($rate, 10)
        ];
    }

    public function getRules(): array
    {
        return [
            'value' => 'required|numeric|min:0.01',
            'currency_from' => 'required|string|min:3|max:3',
            'currency_to' => 'required|string|min:3|max:3|different:currency_from',
        ];
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $value
     * @param array $rates
     * @return array
     * @throws InvalidCurrencyException
     */
    public function convert(
        string $currencyFrom,
        string $currencyTo,
        float $value,
        array $rates
    ): array {
        $commissionMultiplier = config('currency.commission_multiplier');

        try {
            // ENUM сделал сразу на будущее, если захотим добавить монет
            if ($currencyFrom === CoinsEnum::BTC->value) {
                $rate = ($rates[$currencyTo] / $commissionMultiplier);
                $precision = config('currency.short_precision');
            } else {
                $rate = (1 / ($rates[$currencyFrom] * $commissionMultiplier));
                $precision = config('currency.long_precision');;
            }

            return [
                round($rate * $value, $precision),
                $rate
            ];

        } catch (\Throwable $exception) {
            throw new InvalidCurrencyException();
        }
    }
}
