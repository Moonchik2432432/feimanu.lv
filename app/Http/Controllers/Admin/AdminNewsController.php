<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $query = News::query()->with('category');

        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        if ($from) {
            $query->whereDate('publicets_datums', '>=', $from);
        }

        if ($to) {
            $query->whereDate('publicets_datums', '<=', $to);
        }

        $news = $query
            ->orderByDesc('publicets_datums')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.news.index', compact('news', 'q', 'from', 'to'));
    }

    public function create()
    {
        $categories = Category::orderBy('nosaukums')->get();

        return view('admin.news.create', compact('categories'));
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

        return redirect()->route('admin.news')->with('success', 'Ziņa izveidota');
    }

    public function edit($id)
    {
        $post = News::findOrFail($id);
        $categories = Category::orderBy('nosaukums')->get();

        return view('admin.news.edit', compact('post', 'categories'));
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

        if ($data['status'] === 'published' && empty($post->publicets_datums)) {
            $data['publicets_datums'] = now();
        }

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

        return redirect()->route('admin.news')->with('success', 'Ziņa atjaunināta');
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

        return redirect()->route('admin.news')->with('success', 'Ziņa dzēsta');
    }
}