<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserBlock;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isBlockedNow()) {
                $block = UserBlock::with('reason')
                    ->where('user_id', $user->id)
                    ->whereNull('unblocked_at')
                    ->orderByDesc('id')
                    ->first();

                $reason = $block?->reason?->title ?? 'Nav norādīts';
                $customReason = $block?->custom_reason;
                $until = $user->blocked_until?->format('d.m.Y H:i') ?? '-';

                $timeLeft = now()->diffForHumans($user->blocked_until, [
                    'parts' => 2,
                    'short' => false,
                ]);

                $message = "Jūsu konts ir bloķēts.\n";
                $message .= "Iemesls: {$reason}\n";

                if (!empty($customReason)) {
                    $message .= "Komentārs: {$customReason}\n";
                }

                $message .= "Bloķēts līdz: {$until}\n";
                $message .= "Atlikušais laiks: {$timeLeft}";

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => $message]);
            }

            if ($user->is_blocked && $user->blocked_until && now()->greaterThanOrEqualTo($user->blocked_until)) {
                $user->update([
                    'is_blocked' => 0,
                    'blocked_until' => null,
                ]);
            }
        }

        return $next($request);
    }
}