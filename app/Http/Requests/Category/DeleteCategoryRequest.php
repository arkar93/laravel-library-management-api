<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('delete-category');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
