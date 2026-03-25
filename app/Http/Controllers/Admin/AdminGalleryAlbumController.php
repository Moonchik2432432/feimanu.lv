<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminGalleryAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.gallery_albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.gallery_albums.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            $dir = base_path('img/gallery/albums');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('album_') . '.' . $request->file('cover_image')->extension();
            $request->file('cover_image')->move($dir, $filename);

            $data['cover_image'] = 'img/gallery/albums/' . $filename;
        }

        GalleryAlbum::create($data);

        return redirect()->route('admin.gallery.albums')->with('success', 'Albums izveidots');
    }

    public function edit($id)
    {
        $album = GalleryAlbum::findOrFail($id);

        return view('admin.gallery_albums.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            if (!empty($album->cover_image)) {
                $old = base_path($album->cover_image);
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $dir = base_path('img/gallery/albums');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('album_') . '.' . $request->file('cover_image')->extension();
            $request->file('cover_image')->move($dir, $filename);

            $data['cover_image'] = 'img/gallery/albums/' . $filename;
        }

        $album->update($data);

        return redirect()->route('admin.gallery.albums')->with('success', 'Albums atjaunināts');
    }

    public function destroy($id)
    {
        $album = GalleryAlbum::with('images')->findOrFail($id);

        if (!empty($album->cover_image)) {
            $cover = base_path($album->cover_image);
            if (File::exists($cover)) {
                File::delete($cover);
            }
        }

        foreach ($album->images as $image) {
            if (!empty($image->image_path)) {
                $path = base_path($image->image_path);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }

        $album->delete();

        return redirect()->route('admin.gallery.albums')->with('success', 'Albums izdzēsts');
    }
}