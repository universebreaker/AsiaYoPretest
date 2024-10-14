<?php

namespace App\Http\Requests;

use App\Rules\OrderNameRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCheckAndConvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (mb_strtoupper($this->input('currency')) === 'USD') {
            $price = is_numeric($this->input('price')) ? (float) $this->input('price') : 0;
            $this->merge([
                'price' => $price * 31,
                'currency' => 'TWD',
            ]);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], 400));
    }

    public function messages(): array{
        return [
            'price.max' => 'Price is over 2000',
            'currency.in' => 'Currency format is wrong',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'name' => ['required', 'string', new OrderNameRule()],
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric|max:2000',
            'currency' => 'required|in:TWD',
        ];
    }


}
