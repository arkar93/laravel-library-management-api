<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class ViewPermissionRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('view-role');
    }

    public function rules()
    {
        return [
        ];
    }

}
