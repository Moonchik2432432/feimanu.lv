<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request){
        //validacija

        $credentials = $request->validate( [
            'name' => 'required',
            'password' => 'required|min:6'
        ]);

        $username = $request->input('name');
        $password = $request->input('password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/data');
        }

        return back()->withErrors([
            'name' => 'Nekorekts lietotājvārds vai parole.',
        ])->onlyInput('name');
    }



    public function register(Request $request){
        //validacija
        $credentials = $request->validate( [
            'name' => 'required',
            //'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $credentials['name'],
            'password' => Hash::make($credentials['password']),
            'email' => 'a'. uniqid() . '@gmail.com', // Dummy email
        ]);

        return back()->with('success', 'Reģistrācija veiksmīga! Tagad varat pieteikties.');
    }

}
