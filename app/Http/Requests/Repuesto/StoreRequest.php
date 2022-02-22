<?php

namespace App\Http\Requests\Repuesto;

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
            'codigo' => 'required',
            'descripcion' => 'required',
            'clase' => 'required',
            'posicion' => ($this->clase == 'PF' ? 'required' : 'nullable'),
            'medidas' => ($this->clase == 'C' || $this->clase == 'PEM' ? 'required' : 'nullable')
        ];
    }

    public function messages()
    {
        return [
            'clase.required' => 'El campo clase es requerido',
            'codigo.required' => 'El campo código es requerido',
            'descripcion.required' => 'El campo descripción es requerido',
            'medidas.required' => 'El campo medida es requerido',
            'posicion.required' => 'El campo tipo de posición es requerido',

        ];
    }
}
