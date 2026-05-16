<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar que Vue nos envíe el correo y la contraseña
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Buscar al usuario en la base de datos
        $user = User::where('email', $request->email)->first();

        // 3. Verificar que el usuario exista y la contraseña coincida
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas.'
            ], 401); // 401 significa "No autorizado"
        }

        // 4. Generar el token de seguridad de Sanctum
        $token = $user->createToken('admin_token')->plainTextToken;

        // 5. Devolver los datos y el token a Vue
        return response()->json([
            'message' => 'Bienvenido al panel',
            'user' => $user,
            'token' => $token
        ]);
    }
}