<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username'))),
        ]);

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        return auth()->user()->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('customer.menu'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => $validated['username'],
            'email' => null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.menu');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
