<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Services\Api\ApiMethodResolver;

class ApiController extends Controller
{
    public function callMethod(
        ApiRequest $request,
        ApiMethodResolver $resolver
    ) {
        $method = $request->get('method');
        try {
            $result = $resolver->map(
                $method,
                $request->except('method')
            );

            return response()->json(
                [
                    'status' => 'success',
                    'code' => 200,
                    'data' => $result
                ]
            );
        } catch (\Throwable $exception) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ],
                409
            );
        }
    }
}
