<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;

    /**
     * Registrar un nuevo usuario.
     */
    public function register(Request $request)
    {
        $response = null;

        // Validar los datos enviados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string', // El rol es obligatorio
            'photo' => 'nullable|string', // La foto es opcional
        ]);

        if ($validator->fails()) {
            $response = response()->json(['error' => $validator->errors()], 422);
        } elseif ($request->role !== 'entrepreneur') {
            // Restringir el registro solo a usuarios con el rol "entrepreneur"
            $response = response()->json(['error' => 'Solo los usuarios con el rol entrepreneur pueden registrarse.'], 403);
        } else {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'photo' => $request->photo ?? null,
                'balance' => 0, // Balance inicial
            ]);

            // Crear un token de acceso
            
            $token = $user->createToken('auth_token')->plainTextToken;

            $response = response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        }

        return $response;
    }

    /**
     * Iniciar sesión de un usuario.
     */
    public function login(Request $request)
    {
        $response = null;

        $credentials = $request->only('email', 'password');

        // Verificar las credenciales
        if (!Auth::attempt($credentials)) {
            $response = response()->json(['error' => 'Unauthorized'], 401);
        } else {
            $user = Auth::user();

            // Verificar si el usuario tiene el rol "entrepreneur"
            if ($user->role !== 'entrepreneur') {
                $response = response()->json(['error' => 'Solo los usuarios con el rol entrepreneur pueden iniciar sesión.'], 403);
            } else {
                $token = $user->createToken('auth_token')->plainTextToken;

                $response = response()->json([
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            }
        }

        return $response;
    }

    /**
     * Cerrar sesión (eliminar el token).
     */
    public function logout(Request $request)
    {
        $response = null;

        if ($request->user()) {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });

            $response = response()->json(['message' => 'Logged out successfully']);
        }

        return $response;
    }
}
