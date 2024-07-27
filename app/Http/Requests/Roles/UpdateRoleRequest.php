<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update-role');

    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role'),
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }
}
