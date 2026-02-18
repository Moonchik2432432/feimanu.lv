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

        // имя
        $user->name = $data['name'];

        // аватар
        if ($request->hasFile('avatar')) {
            $dir = public_path('img/usersAvatars');

            // если папки нет — создаём
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            // удалить старый (если есть)
            if ($user->avatar) {
                $old = $dir . DIRECTORY_SEPARATOR . $user->avatar;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            // сохранить новый
            $filename = Str::uuid() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move($dir, $filename);

            $user->avatar = $filename;
        }

        $user->save();

        return back()->with('success', 'Profils atjaunināts!');
    }
}
