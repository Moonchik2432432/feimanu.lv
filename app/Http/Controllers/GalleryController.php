<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;

class GalleryController extends Controller
{
    // Список альбомов
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gallery.index', compact('albums'));
    }

    // Один альбом
    public function show($id)
    {
        $album = GalleryAlbum::with('images')->findOrFail($id);

        return view('gallery.show', compact('album'));
    }
}