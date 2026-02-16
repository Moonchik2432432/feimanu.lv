<?php

namespace App\Http\Controllers;

use App\Models\Ieraksts;
use App\Models\Kategorija;

class AktualitatesController extends Controller
{
    public function index()
    {
        $categories = Kategorija::orderBy('nosaukums')->get();

        $news = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->orderByDesc('publicets_datums')
            ->paginate(6);

        return view('aktualitates.index', compact('categories', 'news'));
    }

    public function category($id)
    {
        $categories = Kategorija::orderBy('nosaukums')->get();

        $news = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->where('kategorija_id', $id)
            ->orderByDesc('publicets_datums')
            ->paginate(6);

        return view('aktualitates.index', compact('categories', 'news'));
    }

    public function show($id)
    {
        $post = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->findOrFail($id);

        return view('aktualitates.show', compact('post'));
    }
}
