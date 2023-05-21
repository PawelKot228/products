<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'price' => ['required'],
            'currency' => ['required'],
        ];
    }
}
