<?php

namespace App\Http\Requests;

use App\Rules\IsPositiveInteger;
use App\Rules\IsValidSortOrder;
use Illuminate\Foundation\Http\FormRequest;

class ExchangePairRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'before' => 'sometimes|required|numeric',
            'after' => 'sometimes|required|numeric',
            'page' => [
                'sometimes',
                'required',
                new IsPositiveInteger
            ],
            'per_page' => [
                'sometimes',
                'required',
                new IsPositiveInteger
            ],
            'order' => [
                'sometimes',
                'required',
                new IsValidSortOrder
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function messages()
    {
        return [
            'before.numeric' => 'Before must be a valid numeric timestamp',
            'after.numeric' => 'Before must be a valid numeric timestamp',
        ];
    }
}
