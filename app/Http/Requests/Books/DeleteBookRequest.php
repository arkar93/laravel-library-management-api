<?php

namespace App\Http\Requests\Books;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteBookRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('delete-book');
    }

    public function rules()
    {
        return [];
    }
}
