<?php

namespace App\Exceptions\Currency;

use Exception;

class InvalidCurrencyException extends Exception
{
    protected $code = 409;

    protected $message = 'Invalid Currency';
}
