<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $email = strtolower($googleUser->getEmail());

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $user->update([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $user->name,
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google Customer',
                'username' => $this->uniqueUsername(Str::before($email, '@') ?: 'google_user'),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
                'role' => 'customer',
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('customer.menu');
    }

    private function uniqueUsername(string $base): string
    {
        $base = Str::of($base)
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->limit(40, '')
            ->toString() ?: 'customer';

        $username = $base;
        $counter = 2;

        while (User::where('username', $username)->exists()) {
            $username = $base.'_'.$counter;
            $counter++;
        }

        return $username;
    }
}
