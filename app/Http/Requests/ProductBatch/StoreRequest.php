<?php

declare(strict_types=1);

namespace App\Http\Requests\ProductBatch;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'total_cost' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'business_id.required' => 'Please select a business.',
            'business_id.exists' => 'The selected business does not exist.',
            'name.required' => 'Please enter a batch name.',
            'purchase_date.required' => 'Please select a purchase date.',
            'total_cost.required' => 'Please enter the total cost.',
            'total_cost.numeric' => 'Total cost must be a valid number.',
            'total_cost.min' => 'Total cost must be greater than zero.',
            'total_quantity.required' => 'Please enter the total quantity.',
            'total_quantity.integer' => 'Total quantity must be a whole number.',
            'total_quantity.min' => 'Total quantity must be at least 1.',
        ];
    }
}
