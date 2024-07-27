<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update-user');
    }

    public function rules()
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $userId,
            'password' => 'sometimes|required|min:6',
            'role' => 'sometimes|string|exists:roles,name'
        ];
    }
}

