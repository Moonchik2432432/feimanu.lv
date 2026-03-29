<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            [
                'name.required' => 'Lūdzu, ievadiet vārdu.',
                'name.string' => 'Vārdam jābūt tekstam.',
                'name.max' => 'Vārds nedrīkst būt garāks par 255 simboliem.',

                'avatar.image' => 'Failam jābūt attēlam.',
                'avatar.mimes' => 'Attēlam jābūt JPG, JPEG, PNG vai WEBP formātā.',
                'avatar.max' => 'Attēla izmērs nedrīkst pārsniegt 2 MB.',
            ]
        );

        $user->name = $data['name'];

        if ($request->hasFile('avatar')) {
            $dir = base_path('img/usersAvatars');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            if ($user->avatar) {
                $old = $dir . DIRECTORY_SEPARATOR . $user->avatar;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $filename = Str::uuid() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move($dir, $filename);

            $user->avatar = $filename;
        }

        $user->save();

        return back()->with('success', 'Profils atjaunināts!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'current_password' => ['required'],
                'new_password' => ['required', 'min:6', 'confirmed'],
            ],
            [
                'current_password.required' => 'Lūdzu, ievadiet pašreizējo paroli.',
                'new_password.required' => 'Lūdzu, ievadiet jauno paroli.',
                'new_password.min' => 'Jaunajai parolei jābūt vismaz 6 simboliem.',
                'new_password.confirmed' => 'Jaunās paroles nesakrīt.',
            ]
        );

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Nepareiza pašreizējā parole.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Parole veiksmīgi nomainīta!');
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();

        $request->validate(
            [
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['required'],
            ],
            [
                'email.required' => 'Lūdzu, ievadiet e-pastu.',
                'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
                'email.max' => 'E-pasts nedrīkst būt garāks par 255 simboliem.',
                'email.unique' => 'Šāds e-pasts jau ir reģistrēts.',

                'password.required' => 'Lūdzu, ievadiet paroli.',
            ]
        );

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Nepareiza parole.',
            ]);
        }

        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        return back()->with('success', 'E-pasts veiksmīgi nomainīts!');
    }
}