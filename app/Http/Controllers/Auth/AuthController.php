<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if(! $user || ! Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'username' => ['Las credenciales ingresadas no son correctas.'],
            ]);
        }else{
            $respuesta = Auth::attempt(['username' => $username, 'password' => $password]);
            if($respuesta){
                $user->tokens()->delete();
                return response()->json([
                    'token' => $user->createToken($request->device_name)->plainTextToken,
                    'permissions' => Auth::user()->getAllPermissions()->pluck('name'),
                ]);
            }else{
                return response()->json([
                    'code' => 401
                ]);
            }
        }
    }

    public function comprobarLogin()
    {
        if(Auth::user()){
            return response()->json([
                'code' => 200,
                'user' => 'Usuario logeado'
            ]);
        }else{
            return response()->json([
                'code' => 401,
                'user' => 'Usuario no logeado'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user = User::where('username', $user->username)->first();
        $user->tokens()->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

    }

    public function authpermissions(){
        return response()->json([
            'permisos' => Auth::user()->getAllPermissions()->pluck('name'),
        ]);
    }
}
