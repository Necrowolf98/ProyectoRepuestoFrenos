<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required|unique:categories,name,'.$this->category,
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre de la categoría es requerido',
            'name.unique' => 'El nombre de esta categoría ya se encuentra registrado',
            'description.required' => 'El campo descripción de la categoría es requerido',
        ];
    }
}
