<?php

namespace App\Services\Api;

use App\Exceptions\Api\Exceptions\IncorrectParamsException;
use Illuminate\Support\Facades\Validator;

abstract class AbstractApiService
{
    abstract public function run(array $params): mixed;

    abstract public function getRules(): array;

    public function isValid(array $data): void
    {
        $validator = Validator::make($data, $this->getRules());
        if ($validator->fails()) {
            throw new IncorrectParamsException($validator->getMessageBag(), 423);
        }
    }
}
