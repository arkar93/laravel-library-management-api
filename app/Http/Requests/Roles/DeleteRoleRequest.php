<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRoleRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('delete-role');
    }

    public function rules()
    {
        return [];
    }
}
