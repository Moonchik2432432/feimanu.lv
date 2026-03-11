<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use App\Models\UserBlock;
use App\Models\BlockReason;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $usersQuery = User::query()->select(
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'is_blocked',
            'blocked_until'
        );

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

        $reasons = BlockReason::where('is_active', 1)
            ->orderBy('title')
            ->get();

        return view('admin.users.index', compact('users', 'q', 'from', 'to', 'reasons'));
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
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tu nevari izdzēst savu profilu');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Lietotājs izdzēsts');
    }

    public function block(Request $request, User $user)
    {
        $data = $request->validate([
            'block_reason_id' => ['nullable', 'exists:block_reasons,id'],
            'custom_reason'   => ['nullable', 'string', 'max:1000'],
            'blocked_until'   => ['required', 'date', 'after:now'],
        ]);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tu nevari bloķēt pats sevi');
        }

        UserBlock::create([
            'user_id'         => $user->id,
            'blocked_by'      => auth()->id(),
            'block_reason_id' => $data['block_reason_id'] ?? null,
            'custom_reason'   => $data['custom_reason'] ?? null,
            'blocked_from'    => now(),
            'blocked_until'   => $data['blocked_until'],
        ]);

        $user->update([
            'is_blocked'    => 1,
            'blocked_until' => $data['blocked_until'],
        ]);

        return redirect()->route('admin.users')->with('success', 'Lietotājs bloķēts');
    }

    public function unblock(User $user)
    {
        $activeBlock = UserBlock::where('user_id', $user->id)
            ->whereNull('unblocked_at')
            ->orderByDesc('id')
            ->first();

        if ($activeBlock) {
            $activeBlock->update([
                'unblocked_at' => now(),
                'unblocked_by' => auth()->id(),
            ]);
        }

        $user->update([
            'is_blocked'    => 0,
            'blocked_until' => null,
        ]);

        return redirect()->route('admin.users')->with('success', 'Lietotājs atbloķēts');
    }

    public function history(User $user)
    {
        $blocks = UserBlock::with(['reason', 'blocker', 'unblockedBy'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.users.history', compact('user', 'blocks'));
    }
}