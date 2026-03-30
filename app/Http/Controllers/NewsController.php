<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\GalleryImage;
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

        if ($from && $to && $from > $to) {
            return back()->with('error', 'Datums "No" nevar būt lielāks par datumu "Līdz".');
        }

        $news = News::with('category')
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

        $latestPhotos = GalleryImage::orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('news.index', compact('categories', 'news', 'q', 'from', 'to', 'latestPhotos'));
    }

    public function category(Request $request, $id)
    {
        $categories = Category::orderBy('nosaukums')->get();
        $q = trim($request->query('q', ''));

        $news = News::with('category')
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

        $latestPhotos = GalleryImage::orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('news.index', compact('categories', 'news', 'q', 'latestPhotos'));
    }

    public function show($id)
    {
        $post = News::with(['category', 'comments.user'])
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
        $data = $request->validate(
            [
                'nosaukums' => ['required', 'string', 'max:255'],
                'saturs' => ['required', 'string'],
                'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
                'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            [
                'nosaukums.required' => 'Lūdzu, ievadiet nosaukumu.',
                'nosaukums.string' => 'Nosaukumam jābūt tekstam.',
                'nosaukums.max' => 'Nosaukums nedrīkst būt garāks par 255 simboliem.',

                'saturs.required' => 'Lūdzu, ievadiet saturu.',
                'saturs.string' => 'Saturam jābūt tekstam.',

                'kategorija_id.required' => 'Lūdzu, izvēlieties kategoriju.',
                'kategorija_id.integer' => 'Kategorija nav derīga.',
                'kategorija_id.exists' => 'Izvēlētā kategorija nepastāv.',

                'bilde.image' => 'Failam jābūt attēlam.',
                'bilde.mimes' => 'Attēlam jābūt JPG, JPEG, PNG vai WEBP formātā.',
                'bilde.max' => 'Attēla izmērs nedrīkst pārsniegt 4 MB.',
            ]
        );

        $data['publicets_datums'] = now();

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
    
        $data = $request->validate(
            [
                'nosaukums' => ['required', 'string', 'max:255'],
                'saturs' => ['required', 'string'],
                'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
                'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            [
                'nosaukums.required' => 'Lūdzu, ievadiet nosaukumu.',
                'nosaukums.string' => 'Nosaukumam jābūt tekstam.',
                'nosaukums.max' => 'Nosaukums nedrīkst būt garāks par 255 simboliem.',
    
                'saturs.required' => 'Lūdzu, ievadiet saturu.',
                'saturs.string' => 'Saturam jābūt tekstam.',
    
                'kategorija_id.required' => 'Lūdzu, izvēlieties kategoriju.',
                'kategorija_id.integer' => 'Kategorija nav derīga.',
                'kategorija_id.exists' => 'Izvēlētā kategorija nepastāv.',
    
                'bilde.image' => 'Failam jābūt attēlam.',
                'bilde.mimes' => 'Attēlam jābūt JPG, JPEG, PNG vai WEBP formātā.',
                'bilde.max' => 'Attēla izmērs nedrīkst pārsniegt 4 MB.',
            ]
        );
    
        if (!$request->hasFile('bilde')) {
            unset($data['bilde']);
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
    
        return redirect()->route('news.index')->with('success', 'Ziņa atjaunināta');
    }

    public function destroy($id)
    {
        $post = News::with('comments')->findOrFail($id);

        $post->comments()->delete();

        if (!empty($post->bilde)) {
            $path = base_path($post->bilde);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return redirect()->route('news.index')->with('success', 'Ziņa dzēsta');
    }
}
