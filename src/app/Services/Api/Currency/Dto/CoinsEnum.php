<?php

namespace App\Services\Api\Currency\Dto;

enum CoinsEnum: string
{
    case BTC = 'BTC';
    case ETH = 'ETH';
    case DOGE = 'DOGE';
    case TON = 'TON';

    public static function getAll(): array
    {
        return array_column(self::cases(), 'value');
    }
}
