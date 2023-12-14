<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'method' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
