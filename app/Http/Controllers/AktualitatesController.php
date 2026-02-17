<?php

namespace App\Http\Controllers;

use App\Models\Ieraksts;
use App\Models\Kategorija;
use Illuminate\Http\Request;

class AktualitatesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategorija::orderBy('nosaukums')->get();
        $q = trim($request->query('q', ''));

        $news = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('virsraksts', 'like', "%{$q}%")
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('publicets_datums')
            ->paginate(5)
            ->withQueryString(); 

        return view('aktualitates.index', compact('categories', 'news', 'q'));
    }

    public function category(Request $request, $id)
    {
        $categories = Kategorija::orderBy('nosaukums')->get();
        $q = trim($request->query('q', ''));

        $news = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->where('kategorija_id', $id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('virsraksts', 'like', "%{$q}%")
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('publicets_datums')
            ->paginate(5)
            ->withQueryString();

        return view('aktualitates.index', compact('categories', 'news', 'q'));
    }

    public function show($id)
    {
        $post = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->findOrFail($id);

        return view('aktualitates.show', compact('post'));
    }
}
