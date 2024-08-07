<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Método para manejar el inicio de sesión
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Autenticación exitosa
            return response()->json(['message' => 'Autenticación satisfactoria'], 200);
        } else {
            // Error en la autenticación
            return response()->json(['message' => 'Error en la autenticación'], 401);
        }
    }

    // Método para manejar el registro
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el nuevo usuario
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Autenticar al usuario recién registrado
        Auth::login($user);

        // Responder con un mensaje de éxito
        return response()->json(['message' => 'Registro exitoso'], 201);
    }
}
