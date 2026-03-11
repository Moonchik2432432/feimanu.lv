<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isBlockedNow()) {
                $block = UserBlock::with('reason')
                    ->where('user_id', $user->id)
                    ->whereNull('unblocked_at')
                    ->orderByDesc('id')
                    ->first();

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $block?->reason?->title ?? 'Nav norādīts';
                $customReason = $block?->custom_reason;
                $until = $user->blocked_until?->format('d.m.Y H:i') ?? '-';

                $timeLeft = now()->diffForHumans($user->blocked_until, [
                    'parts' => 2,
                    'short' => false,
                ]);

                $message = "Jūsu konts ir bloķēts.\n";
                $message .= "Iemesls: {$reason}\n";

                if (!empty($customReason)) {
                    $message .= "Komentārs: {$customReason}\n";
                }

                $message .= "Bloķēts līdz: {$until}\n";
                $message .= "Atlikušais laiks: {$timeLeft}";

                return back()
                    ->withErrors(['email' => $message])
                    ->onlyInput('email');
            }

            if ($user->is_blocked && $user->blocked_until && now()->greaterThanOrEqualTo($user->blocked_until)) {
                $user->update([
                    'is_blocked' => 0,
                    'blocked_until' => null,
                ]);
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Nepareizs e-pasts vai parole',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }
}