<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $commentsCount = method_exists($user, 'komentari') ? $user->komentari()->count() : null;

        return view('profile.show', compact('user', 'commentsCount'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Обновление имени
        $user->name = $data['name'];

        // Если загружен новый аватар
        if ($request->hasFile('avatar')) {

            // Удаляем старый аватар (если есть и не дефолт)
            if ($user->avatar && File::exists(public_path('img/usersAvatars/' . $user->avatar))) {
                File::delete(public_path('img/usersAvatars/' . $user->avatar));
            }

            // Генерируем уникальное имя
            $filename = Str::uuid() . '.' . $request->avatar->extension();

            // Сохраняем файл
            $request->avatar->move(public_path('img/usersAvatars'), $filename);

            // Сохраняем имя в БД
            $user->avatar = $filename;
        }

        $user->save();

        return back()->with('success', 'Profils atjaunināts!');
    }
}
