<?php

namespace App\Http\Requests\Books;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ViewRentedBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'status' => 'sometimes|in:rented,returned',

        ];
    }
}
