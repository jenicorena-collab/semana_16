<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $datos = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (!Auth::attempt($datos)) {
            throw ValidationException::withMessages(['email' => ['Las credenciales no son válidas.']]);
        }
        $user = $request->user();
        return response()->json(['token' => $user->createToken('bruno')->plainTextToken, 'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }
}
