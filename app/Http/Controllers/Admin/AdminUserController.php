<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use App\Models\UserBlock;
use App\Models\BlockReason;
use Illuminate\Http\Request;
use App\Mail\UserDeletedMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
 
class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        if ($from && $to && $from > $to) {
            return back() -> with('error', 'Datums "No" nevar būt lielāks par datumu "Līdz".');
        }
 
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
 
        $reasons = BlockReason::orderBy('title')->get();
 
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
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tu nevari bloķēt pats sevi');
        }
     
        $data = $request->validate([
            'block_reason_id' => ['nullable', 'exists:block_reasons,id'],
            'custom_reason' => ['nullable', 'string', 'max:1000'],
            'blocked_until' => ['nullable', 'date', 'after:now'],
        ], [
            'string' => 'Laukam :attribute jābūt tekstam.',
            'max' => 'Lauks :attribute nedrīkst būt garāks par :max rakstzīmēm.',
            'exists' => 'Izvēlētais :attribute nav derīgs.',
            'date' => 'Laukam :attribute jābūt derīgam datumam.',
            'after' => 'Laukam :attribute jābūt datumam pēc šī brīža.',
        ], [
            'block_reason_id' => 'bloķēšanas iemesls',
            'custom_reason' => 'papildu iemesls',
            'blocked_until' => 'bloķēšanas termiņš',
        ]);
     
        $blockedUntil = !empty($data['blocked_until'])
            ? $data['blocked_until']
            : now()->addMonth();
     
        UserBlock::create([
            'user_id' => $user->id,
            'blocked_by' => auth()->id(),
            'block_reason_id' => $data['block_reason_id'] ?? null,
            'custom_reason' => $data['custom_reason'] ?? null,
            'blocked_from' => now(),
            'blocked_until' => $blockedUntil,
        ]);
     
        $user->update([
            'is_blocked' => 1,
            'blocked_until' => $blockedUntil,
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
            'is_blocked' => 0,
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

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'delete_reason' => ['required', 'string', 'max:1000'],
        ], [
            'delete_reason.required' => 'Lūdzu, norādiet dzēšanas iemeslu.',
            'delete_reason.string' => 'Dzēšanas iemeslam jābūt tekstam.',
            'delete_reason.max' => 'Dzēšanas iemesls nedrīkst būt garāks par 1000 simboliem.',
        ]);
    
        $user = User::findOrFail($id);
    
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Jūs nevarat dzēst savu kontu.');
        }
    
        try {
            if (!empty($user->email)) {
                Mail::to($user->email)->send(new UserDeletedMail($user, $request->delete_reason));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Neizdevās nosūtīt e-pastu lietotājam.');
        }
    
        if (!empty($user->avatar) && $user->avatar !== 'default_avatar.jpg') {
            $avatarPath = public_path('img/usersAvatars/' . $user->avatar);
            if (File::exists($avatarPath)) {
                File::delete($avatarPath);
            }
        }
    
        if (method_exists($user, 'comments')) {
            $user->comments()->delete();
        }
    
        if (method_exists($user, 'blocks')) {
            $user->blocks()->delete();
        }
    
        if (method_exists($user, 'contactMessages')) {
            $user->contactMessages()->delete();
        }
    
        $user->delete();
    
        return redirect()->route('admin.users')->with('success', 'Lietotāja konts tika dzēsts un e-pasts nosūtīts.');
    }
}
