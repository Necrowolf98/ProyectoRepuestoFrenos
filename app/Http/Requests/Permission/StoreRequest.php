<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:permissions',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre del permiso es requerido',
            'name.unique' => 'El nombre de este permiso ya se encuentra registrado',
            'description.required' => 'El campo descripción del permiso es requerido',
        ];
    }
}
