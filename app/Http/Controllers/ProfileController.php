<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        // если у User есть связь komentari(), можно посчитать так:
        $commentsCount = method_exists($user, 'komentari') ? $user->komentari()->count() : null;

        return view('profile.show', compact('user', 'commentsCount'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // аватар необязателен, можно позже добавить upload
        ]);

        $user->update([
            'name' => $data['name'],
        ]);

        return back()->with('success', 'Profils atjaunināts!');
    }
}
