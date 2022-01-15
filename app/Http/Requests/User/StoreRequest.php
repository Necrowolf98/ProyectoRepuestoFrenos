<?php

namespace App\Http\Requests\User;

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
            'name' => 'required',
            'lastname' => 'required',
            'type_document' => 'required',
            'number_document' => ($this->type_document == 'DNI' ? 'required|min:9|unique:people|ends_with:A,B,C,D,E,F,G,H,I,J,Q,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z' : ($this->type_document == 'RUC' ? 'required|min:13|unique:people|ends_with:001' : 'required|min:10|unique:people')),
            'direction' => 'required',
            'phone' => 'required|min:12',
            'email' => 'required|unique:people|email',
            'username' => 'required|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombres es requerido',
            'lastname.required' => 'El campo apellidos es requerido',
            'type_document.required' => 'El campo tipo documento es requerido',
            'number_document.required' => 'El campo numero de documento es requerido',
            'number_document.unique' => 'Este numero de documento ya se encuentra registrado',
            'number_document.min' => ($this->type_document == 'DNI' ? 'El campo número de documento requiere 8 números 1 letra' :($this->type_document == 'RUC' ? 'El campo número de documento requiere 13 números' : 'El campo número de documento requiere 10 números')),

            'number_document.ends_with' => ($this->type_document == 'DNI') ? 'El campo número de documento debe terminar en una letra' : 'El campo número de documento debe terminar en 001',
            'direction.required' => 'El campo direccion es requerido',
            'phone.required' => 'El campo telefono es requerido',
            'phone.min' => 'El campo telefono debe tener un mínimo de 10 números',
            'email.required' => 'El campo email es requerido',
            'email.unique' => 'Este email ya se encuentra registrado',
            'email.email' => 'Utilize esta jerarquía correo@correo.com',

            'username.unique' => 'Este usuario ya se encuentra registrado',
            'username.required' => 'El campo usuario es requerido',

            'password.required' => 'El campo contraseña es requerido',
            'password.min' => 'Tu contraseña debe tener almenos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',

            'role_id.required' => 'El campo rol es requerido',
        ];
    }
}
