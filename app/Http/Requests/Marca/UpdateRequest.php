<?php

namespace App\Http\Requests\Marca;

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
            'casa_marca' => 'required|unique:marcas,casa_marca,' .$this->marca,
        ];
    }

    public function messages()
    {
        return [
            'casa_marca.required' => 'El campo nombre de la marca del vehiculo es requerido',
            'casa_marca.unique' => 'El nombre de esta marca ya se encuentra registrado',
        ];
    }
}
