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
}