<?php

namespace App\Http\Controllers;

use App\Models\Ieraksts;
use App\Models\Kategorija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    // Ziņu saraksts
    public function index(Request $request)
    {
        $categories = Kategorija::orderBy('nosaukums')->get();

        $q = trim($request->query('q', ''));
        $from = $request->query('from');
        $to = $request->query('to');

        $news = Ieraksts::with('kategorija')
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

    // Ziņas pēc kategorijas
    public function category(Request $request, $id)
    {
        $categories = Kategorija::orderBy('nosaukums')->get();
        $q = trim($request->query('q', ''));

        $news = Ieraksts::with('kategorija')
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

    // Viena ziņa
    public function show($id)
    {
        $post = Ieraksts::with(['kategorija', 'komentari.user'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('news.show', compact('post'));
    }

    // Create forma
    public function create()
    {
        $categories = Kategorija::orderBy('nosaukums')->get();

        return view('news.create', compact('categories'));
    }

    // Saglabāt ziņu
    public function store(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required','string','max:255'],
            'saturs' => ['required','string'],
            'kategorija_id' => ['required','integer','exists:kategorija,kategorija_id'],
            'bilde' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'status' => ['required','in:draft,published']
        ]);

        $data['publicets_datums'] = $data['status'] === 'published' ? now() : null;

        if ($request->hasFile('bilde')) {

            $dir = base_path('img/aktualitates');

            if(!File::exists($dir)){
                File::makeDirectory($dir,0755,true);
            }

            $filename = uniqid('news_').'.'.$request->file('bilde')->extension();
            $request->file('bilde')->move($dir,$filename);

            $data['bilde'] = 'img/aktualitates/'.$filename;
        }

        Ieraksts::create($data);

        return redirect()->route('news.index')
            ->with('success','Ziņa izveidota');
    }

    // Edit forma
    public function edit($id)
    {
        $post = Ieraksts::findOrFail($id);
        $categories = Kategorija::orderBy('nosaukums')->get();

        return view('news.edit', compact('post','categories'));
    }

    // Update
    public function update(Request $request,$id)
    {
        $post = Ieraksts::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required','string','max:255'],
            'saturs' => ['required','string'],
            'kategorija_id' => ['required','integer','exists:kategorija,kategorija_id'],
            'bilde' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'status' => ['required','in:draft,published']
        ]);

        if($request->hasFile('bilde')){

            if(!empty($post->bilde)){
                $old = base_path($post->bilde);
                if(File::exists($old)){
                    File::delete($old);
                }
            }

            $dir = base_path('img/aktualitates');

            if(!File::exists($dir)){
                File::makeDirectory($dir,0755,true);
            }

            $filename = uniqid('news_').'.'.$request->file('bilde')->extension();
            $request->file('bilde')->move($dir,$filename);

            $data['bilde'] = 'img/aktualitates/'.$filename;
        }

        $post->update($data);

        return redirect()->route('news.index')
            ->with('success','Ziņa atjaunināta');
    }

    // Dzēst ziņu
    public function destroy($id)
    {
        $post = Ieraksts::with('komentari')->findOrFail($id);

        $post->komentari()->delete();

        if(!empty($post->bilde)){
            $path = public_path(ltrim($post->bilde,'/'));
            if(File::exists($path)){
                File::delete($path);
            }
        }

        $post->delete();

        return redirect()->route('news.index')
            ->with('success','Ziņa dzēsta');
    }
}