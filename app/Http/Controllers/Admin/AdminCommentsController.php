<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;

class AdminCommentsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = Comments::query()
            ->leftJoin('users', 'komentari.user_id', '=', 'users.id')
            ->leftJoin('ieraksts', 'komentari.ieraksts_id', '=', 'ieraksts.ieraksts_id')
            ->select([
                'komentari.komentars_id',
                'komentari.user_id',
                'komentari.ieraksts_id',
                'komentari.text',
                'komentari.izveidots_datums',
                'users.name as user_name',
                'ieraksts.nosaukums as ieraksts_title',
            ]);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('komentari.text', 'like', "%{$q}%")
                    ->orWhere('users.name', 'like', "%{$q}%")
                    ->orWhere('ieraksts.nosaukums', 'like', "%{$q}%");
            });
        }

        $comments = $query
            ->orderByDesc('komentari.izveidots_datums')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.komentari', compact('comments', 'q'));
    }

    public function destroy($id)
    {
        $comment = Comments::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments')->with('success', 'Komentārs izdzēsts');
    }
}