<?php

namespace App\Http\Requests\Books;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookReturnFormRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('return-book');
    }

    public function rules()
    {
        return [
            'rental_id' => 'required|exists:book_rentals,id',
        ];
    }
}
