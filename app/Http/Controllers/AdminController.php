<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ieraksts;
use App\Models\Kategorija;
use Illuminate\Support\Facades\DB;

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

        return view('admin.users_show', compact('user', 'comments'));
    }

    // Aktualitates
    public function ieraksti(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $query = \App\Models\Ieraksts::query()->with('kategorija');

        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        if ($from) {
            $query->whereDate('publicets_datums', '>=', $from);
        }

        if ($to) {
            $query->whereDate('publicets_datums', '<=', $to);
        }

        $ieraksti = $query->orderByDesc('publicets_datums')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.ieraksti', compact('ieraksti', 'q', 'from', 'to'));
    }

    //Kategorija
    public function kategorijas(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = Kategorija::query();

        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        $categories = $query->orderBy('nosaukums')->paginate(15)->appends($request->query());

        return view('admin.kategorijas', compact('categories', 'q'));
    }

    public function kategorijasCreate()
    {
        return view('admin.kategorija_create');
    }

    public function kategorijasStore(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:100', 'unique:kategorija,nosaukums'],
        ]);

        Kategorija::create($data);

        return redirect()->route('admin.kategorijas')->with('success', 'Kategorija pievienota');
    }

    public function kategorijasEdit($id)
    {
        $category = Kategorija::findOrFail($id);

        return view('admin.kategorija_edit', compact('category'));
    }

    public function kategorijasUpdate(Request $request, $id)
    {
        $category = Kategorija::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:100', 'unique:kategorija,nosaukums,' . $category->kategorija_id . ',kategorija_id'],
        ]);

        $category->update($data);

        return redirect()->route('admin.kategorijas')->with('success', 'Kategorija atjaunināta');
    }

    public function kategorijasDestroy($id)
    {
        $category = Kategorija::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.kategorijas')->with('success', 'Kategorija izdzēsta');
    }
}