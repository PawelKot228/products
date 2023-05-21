<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Str;

class ProductIndexRequest extends FormRequest
{
    private array $orderColumns = [
        'id',
        'name',
        'created_at',
    ];

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'currency' => ['sometimes', Rule::enum(Currency::class)],
            'priceStart' => ['sometimes', 'numeric', 'min:0', 'required_if:priceEnd,lte:priceEnd'],
            'priceEnd' => ['sometimes', 'numeric', 'min:0', 'required_if:priceStart,gte:priceStart'],
            'orderBy' => ['sometimes', Rule::in($this->orderColumns)],
            'sort' => ['sometimes', 'in:desc,asc'],
            'limit' => ['sometimes', 'numeric', 'min:5', 'max:100'],
        ];
    }

    public function messages(): array
    {
        $allowedColumns = collect($this->orderColumns)->join(', ', ' and ');
        $allowedCurrencies = collect(Currency::cases())->pluck('value')->join(', ', ' and ');

        return [
            'currency' => "Currently only $allowedCurrencies currencies are allowed",
            'orderBy.in' => "Only columns $allowedColumns are allowed.",
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('currency')) {
            $this->merge(['currency' => Str::upper($this->get('currency')),]);
        }

        if ($this->has('orderBy')) {
            $this->merge(['orderBy' => Str::lower($this->get('orderBy')),]);
        }

        if ($this->has('sort')) {
            $this->merge(['sort' => Str::lower($this->get('sort')),]);
        }
    }
}
