<?php

namespace App\Services\Api\Currency\Vendors;

abstract class AbstractCurrencyClient
{
    abstract public function getRates();
}
