<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        Comment::create([
            'ieraksts_id' => (int)$id,
            'user_id' => auth()->id(),
            'text' => $request->text,
            'izveidots_datums' => now(),
        ]);

        return back();
    }

    public function destroy($id)
    {
    $comment = Comment::findOrFail($id);

    // Можно удалить только свой комментарий
    if ($comment->user_id !== auth()->id()) {
        abort(403);
    }

    $comment->delete();

    return back();
    }

}
