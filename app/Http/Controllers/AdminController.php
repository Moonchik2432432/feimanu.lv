<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $q    = trim((string) $request->get('q', ''));
        $from = $request->get('from'); // YYYY-MM-DD
        $to   = $request->get('to');   // YYYY-MM-DD

        $usersQuery = User::query()->select('id','name','email','role','created_at');

        // Поиск по id / name / email
        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                if (ctype_digit($q)) {
                    $sub->orWhere('id', (int) $q);
                }
                $sub->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // Фильтр по дате регистрации (created_at)
        if ($from) {
            $usersQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $usersQuery->whereDate('created_at', '<=', $to);
        }

        $users = $usersQuery
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query()); // сохраняет q/from/to при пагинации

        return view('admin.users', compact('users', 'q', 'from', 'to'));
    }

    public function edit(User $user)
    {
        return view('admin.users_edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'in:admin,user'],
        ]);

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Lietotājs atjaunināts');
    }

    public function destroy(User $user)
    {
        // защита: не дать удалить самого себя
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tu nevari izdzēst savu profilu');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Lietotājs izdzēsts');
    }

public function show(\App\Models\User $user)
{
    $comments = \App\Models\Komentars::query()
        ->where('user_id', $user->id)
        ->leftJoin('aktualitates', 'komentari.aktualitate_id', '=', 'aktualitates.id')
        ->orderByDesc('komentari.created_at')
        ->select([
            'komentari.id',
            'komentari.saturs',
            'komentari.created_at',
            'komentari.aktualitate_id',
            'aktualitates.virsraksts as aktualitate_title',
        ])
        ->get();

    return view('admin.users_show', compact('user', 'comments'));
}
}