<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username'))),
        ]);

        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $user = User::where('username', strtolower($validated['username']))->first();

        if (! $user || ! $user->password || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('postman-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Token revoked successfully.',
        ]);
    }
}
