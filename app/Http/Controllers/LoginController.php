<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Lietotajs;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'epasts' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt(['epasts' => $credentials['epasts'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/data');
        }

        return back()->withErrors([
            'epasts' => 'Nekorekts epasts vai parole.',
        ])->onlyInput('epasts');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'vards' => 'required|string|max:100',
            'uzvards' => 'required|string|max:100',
            'epasts' => 'required|email|max:150|unique:lietotajs,epasts',
            'password' => 'required|min:6',
        ]);

        Lietotajs::create([
            'vards' => $data['vards'],
            'uzvards' => $data['uzvards'],
            'epasts' => $data['epasts'],
            'parole' => Hash::make($data['password']),
            'loma' => 'user',
        ]);

        return back()->with('success', 'Reģistrācija veiksmīga! Tagad varat pieteikties.');
    }
}
