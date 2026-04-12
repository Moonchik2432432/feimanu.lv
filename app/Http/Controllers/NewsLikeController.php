<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsLike;
use Illuminate\Http\Request;

class NewsLikeController extends Controller
{
    public function toggle($id)
    {
        $news = News::findOrFail($id);

        $like = NewsLike::where('user_id', auth()->id())
            ->where('ieraksts_id', $news->ieraksts_id)
            ->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Patīk noņemts.');
        }

        NewsLike::create([
            'user_id' => auth()->id(),
            'ieraksts_id' => $news->ieraksts_id,
        ]);

        return back()->with('success', 'Patīk pievienots.');
    }
}
