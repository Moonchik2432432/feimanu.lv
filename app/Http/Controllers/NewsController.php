<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('nosaukums')->get();

        $q = trim($request->query('q', ''));
        $from = $request->query('from');
        $to = $request->query('to');

        $news = News::with('category')
            ->where('status', 'published')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nosaukums', 'like', "%{$q}%")
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('publicets_datums', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('publicets_datums', '<=', $to);
            })
            ->orderByDesc('publicets_datums')
            ->paginate(5)
            ->withQueryString();

        return view('news.index', compact('categories', 'news', 'q', 'from', 'to'));
    }

    public function category(Request $request, $id)
    {
        $categories = Category::orderBy('nosaukums')->get();
        $q = trim($request->query('q', ''));

        $news = News::with('category')
            ->where('status', 'published')
            ->where('kategorija_id', $id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nosaukums', 'like', "%{$q}%")
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('publicets_datums')
            ->paginate(5)
            ->withQueryString();

        return view('news.index', compact('categories', 'news', 'q'));
    }

    public function show($id)
    {
        $post = News::with(['category', 'comments.user'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('news.show', compact('post'));
    }

    public function create()
    {
        $categories = Category::orderBy('nosaukums')->get();

        return view('news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:255'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $data['publicets_datums'] = $data['status'] === 'published' ? now() : null;

        if ($request->hasFile('bilde')) {
            $dir = base_path('img/aktualitates');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('news_') . '.' . $request->file('bilde')->extension();
            $request->file('bilde')->move($dir, $filename);

            $data['bilde'] = 'img/aktualitates/' . $filename;
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'Ziņa izveidota');
    }

    public function edit($id)
    {
        $post = News::findOrFail($id);
        $categories = Category::orderBy('nosaukums')->get();

        return view('news.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = News::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:255'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('bilde')) {
            if (!empty($post->bilde)) {
                $old = base_path($post->bilde);
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $dir = base_path('img/aktualitates');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('news_') . '.' . $request->file('bilde')->extension();
            $request->file('bilde')->move($dir, $filename);

            $data['bilde'] = 'img/aktualitates/' . $filename;
        }

        $post->update($data);

        return redirect()->route('news.index')->with('success', 'Ziņa atjaunināta');
    }

    public function destroy($id)
    {
        $post = News::with('comments')->findOrFail($id);

        $post->comments()->delete();

        if (!empty($post->bilde)) {
            $path = public_path(ltrim($post->bilde, '/'));
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return redirect()->route('news.index')->with('success', 'Ziņa dzēsta');
    }
}