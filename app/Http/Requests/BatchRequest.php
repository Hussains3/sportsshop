<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'product_id' => ['required', 'exists:products,id'],
            'batch_number' => ['required', 'string', 'max:255'],
            'initial_quantity' => ['required', 'integer', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'min_selling_price' => ['required', 'numeric', 'min:0', 'gte:purchase_price'],
            'status' => ['required', 'in:active,expired,depleted']
        ];

        if ($this->isMethod('POST')) {
            $rules['batch_number'][] = 'unique:batches';
        } else {
            $rules['batch_number'][] = 'unique:batches,batch_number,' . $this->batch?->id;
        }

        return $rules;
    }
}
