<?php

namespace App\Http\Requests\Vehiculo;

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
            'repuestofreno_id' => 'required',
            'casa_marca_id' => 'required',
            'modelo' => 'required',
            'anio_vehiculo' => 'required|numeric|digits:4'
        ];
    }

    public function messages()
    {
        return [
            'repuestofreno_id.required' => 'El campo repuesto es requerido',
            'casa_marca_id.required' => 'El campo marca de vehiculo es requerido',
            'modelo.required' => 'El campo modelo del vehiculo es requerido',
            'anio_vehiculo.required' => 'El campo año del vehiculo es requerido',
            'anio_vehiculo.numeric' => 'El campo año del vehiculo requiere un año.',
            'anio_vehiculo.digits' => 'El campo año del vehiculo requiere solamente 4 números.'
        ];
    }
}
