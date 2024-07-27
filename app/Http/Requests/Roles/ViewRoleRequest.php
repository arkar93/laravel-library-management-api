<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class ViewRoleRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('view-role');
    }

    public function rules()
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }

}
