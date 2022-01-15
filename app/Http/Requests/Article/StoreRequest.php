<?php

namespace App\Http\Requests\Article;

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
            'categorie_id' => 'required',
            'code' => 'required',
            'name' => 'required|unique:articles',
            'sale_price' => 'required|gt:0',
            'stock' => 'required|numeric',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'categorie_id.required' => 'El campo categoría es requerida',
            'code.required' => 'El campo código es requerido',
            'name.required' => 'El campo nombre es requerido',
            'name.unique' => 'Este nombre de producto ya esta registrado',
            'sale_price.required' => 'El campo precio venta es requerido',
            'sale_price.gt' => 'El precio de venta debe ser mayor a cero',
            'stock.required' => 'El campo stock es requerido',
            'description.required' => 'El campo descripcion es requerido',
        ];
    }
}
