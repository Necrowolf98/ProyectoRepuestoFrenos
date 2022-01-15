<?php

namespace App\Http\Requests\Role;

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
            'name' => 'required|unique:roles',
            'description' => 'required',
            'permissions' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre del rol es requerido',
            'name.unique' => 'El nombre de este rol ya se encuentra registrado',
            'description.required' => 'El campo descripción del rol es requerido',
            'permissions.required' => 'Seleccione un permiso',
        ];
    }
}
