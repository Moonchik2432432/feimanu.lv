<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $usersQuery = User::query()->select('id', 'name', 'email', 'role', 'created_at');

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                if (ctype_digit($q)) {
                    $sub->orWhere('id', (int) $q);
                }

                $sub->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($from) {
            $usersQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $usersQuery->whereDate('created_at', '<=', $to);
        }

        $users = $usersQuery
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.users.index', compact('users', 'q', 'from', 'to'));
    }

    public function show(User $user)
    {
        $comments = Comment::query()
            ->leftJoin('ieraksts', 'komentari.ieraksts_id', '=', 'ieraksts.ieraksts_id')
            ->where('komentari.user_id', $user->id)
            ->orderByDesc('komentari.izveidots_datums')
            ->select([
                'komentari.komentars_id',
                'komentari.text',
                'komentari.izveidots_datums',
                'komentari.ieraksts_id',
                'ieraksts.nosaukums as ieraksts_title',
            ])
            ->get();

        return view('admin.users.show', compact('user', 'comments'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
}