<?php

namespace App\Http\Requests\Books;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookRentalFormRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('rent-book');
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
