<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $lastComment = Comment::where('user_id', auth()->id())
            ->orderByDesc('izveidots_datums')
            ->first();

        if ($lastComment) {
            $secondsPassed = $lastComment->izveidots_datums->diffInSeconds(now());

            if ($secondsPassed < 30) {
                $wait = 30 - $secondsPassed;

                return back()->with('error', "Tu vari rakstīt nākamo komentāru pēc {$wait} sek.");
            }
        }

        Comment::create([
            'ieraksts_id' => (int) $id,
            'user_id' => auth()->id(),
            'text' => $request->text,
            'izveidots_datums' => now(),
        ]);

        return back()->with('success', 'Komentārs pievienots');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Komentārs izdzēsts');
    }
}