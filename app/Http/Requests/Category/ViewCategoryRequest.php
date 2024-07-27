<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class ViewCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-category');
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
