<?php

declare(strict_types=1);

namespace App\Http\Requests\Investment;

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
            'user_id' => 'required|exists:users,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'amount' => 'required|numeric|min:0',
            'share_percentage' => 'sometimes|numeric|min:0|max:100',
            'invested_at' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'business_id.required' => 'Please select a business.',
            'business_id.exists' => 'The selected business does not exist.',
            'user_id.required' => 'Please select an investor.',
            'user_id.exists' => 'The selected investor does not exist.',
            'product_batch_id.required' => 'Please select a product batch.',
            'product_batch_id.exists' => 'The selected product batch does not exist.',
            'amount.required' => 'Please enter the investment amount.',
            'amount.numeric' => 'Investment amount must be a valid number.',
            'amount.min' => 'Investment amount must be greater than zero.',
            'share_percentage.numeric' => 'Share percentage must be a valid number.',
            'share_percentage.min' => 'Share percentage must be at least 0.',
            'share_percentage.max' => 'Share percentage cannot exceed 100%.',
            'invested_at.required' => 'Please select an investment date.',
        ];
    }
}
