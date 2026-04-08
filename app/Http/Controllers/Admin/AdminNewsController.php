<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        if ($from && $to && $from > $to) {
            return back()->with('error', 'Datums "No" nevar būt lielāks par datumu "Līdz".');
        }

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
        $galleryImages = GalleryImage::orderByDesc('created_at')->get();

        return view('admin.news.create', compact('categories', 'galleryImages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:55'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_image' => ['nullable', 'string'],
            'pasakuma_datums' => ['nullable', 'date'],
        ], [
            'required' => 'Lauks :attribute ir obligāts.',
            'string' => 'Laukam :attribute jābūt tekstam.',
            'integer' => 'Laukam :attribute jābūt skaitlim.',
            'exists' => 'Izvēlētais :attribute nav derīgs.',
            'image' => 'Laukam :attribute jābūt attēlam.',
            'mimes' => 'Laukam :attribute jābūt JPG, JPEG, PNG vai WEBP failam.',
            'max' => 'Lauks :attribute nedrīkst būt lielāks par 4 MB.',
        ], [
            'nosaukums' => 'nosaukums',
            'saturs' => 'saturs',
            'kategorija_id' => 'kategorija',
            'bilde' => 'attēls',
            'gallery_image' => 'galerijas attēls',
        ]);

        $data['publicets_datums'] = now();
        $data['bilde'] = null;

        if ($request->hasFile('bilde')) {
            $dir = base_path('img/aktualitates');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('news_') . '.' . $request->file('bilde')->extension();
            $request->file('bilde')->move($dir, $filename);

            $data['bilde'] = 'img/aktualitates/' . $filename;
        } elseif ($request->filled('gallery_image')) {
            $data['bilde'] = $request->gallery_image;
        }

        News::create($data);

        return redirect()->route('admin.news')->with('success', 'Ziņa izveidota');
    }

    public function edit($id)
    {
        $post = News::findOrFail($id);
        $categories = Category::orderBy('nosaukums')->get();
        $galleryImages = GalleryImage::orderByDesc('created_at')->get();

        return view('admin.news.edit', compact('post', 'categories', 'galleryImages'));
    }

    public function update(Request $request, $id)
    {
        $post = News::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:55'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_image' => ['nullable', 'string'],
            'pasakuma_datums' => ['nullable', 'date'],
        ], [
            'required' => 'Lauks :attribute ir obligāts.',
            'string' => 'Laukam :attribute jābūt tekstam.',
            'integer' => 'Laukam :attribute jābūt skaitlim.',
            'exists' => 'Izvēlētais :attribute nav derīgs.',
            'image' => 'Laukam :attribute jābūt attēlam.',
            'mimes' => 'Laukam :attribute jābūt JPG, JPEG, PNG vai WEBP failam.',
            'max' => 'Lauks :attribute nedrīkst būt lielāks par 4 MB.',
        ], [
            'nosaukums' => 'nosaukums',
            'saturs' => 'saturs',
            'kategorija_id' => 'kategorija',
            'bilde' => 'attēls',
            'gallery_image' => 'galerijas attēls',
        ]);

        if ($request->hasFile('bilde')) {
            if (!empty($post->bilde) && str_starts_with($post->bilde, 'img/aktualitates/')) {
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
        } elseif ($request->filled('gallery_image')) {
            if (!empty($post->bilde) && str_starts_with($post->bilde, 'img/aktualitates/')) {
                $old = base_path($post->bilde);
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $data['bilde'] = $request->gallery_image;
        } else {
            unset($data['bilde']);
        }

        $post->update($data);

        return redirect()->route('admin.news')->with('success', 'Ziņa atjaunināta');
    }

    public function destroy($id)
    {
        $post = News::with('comments')->findOrFail($id);

        $post->comments()->delete();

        if (!empty($post->bilde) && str_starts_with($post->bilde, 'img/aktualitates/')) {
            $path = base_path($post->bilde);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return redirect()->route('admin.news')->with('success', 'Ziņa dzēsta');
    }
}
