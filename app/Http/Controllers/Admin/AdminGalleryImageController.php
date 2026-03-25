<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminGalleryImageController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = trim($request->query('q', ''));
        $albumId = $request->query('album_id');
        $from = $request->query('from');
        $to = $request->query('to');
    
        if ($from && $to && $from > $to) {
            return back()->with('error', 'Datums "No" nevar būt lielāks par datumu "Līdz".');
        }
    
        $albums = \App\Models\GalleryAlbum::orderBy('title')->get();
    
        $images = \App\Models\GalleryImage::with('album')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->when($albumId, function ($query) use ($albumId) {
                $query->where('album_id', $albumId);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    
        return view('admin.gallery_images.index', compact(
            'images',
            'albums',
            'q',
            'albumId',
            'from',
            'to'
        ));
    }

    public function create()
    {
        $albums = GalleryAlbum::orderBy('title')->get();

        return view('admin.gallery_images.create', compact('albums'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'album_id' => ['required', 'integer', 'exists:gallery_albums,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'image_path' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $album = GalleryAlbum::findOrFail($data['album_id']);
        $folder = base_path('img/gallery/album_' . $album->id);

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $filename = uniqid('photo_') . '.' . $request->file('image_path')->extension();
        $request->file('image_path')->move($folder, $filename);

        $data['image_path'] = 'img/gallery/album_' . $album->id . '/' . $filename;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        GalleryImage::create($data);

        return redirect()->route('admin.gallery.images')->with('success', 'Fotogrāfija pievienota');
    }

    public function edit($id)
    {
        $image = GalleryImage::findOrFail($id);
        $albums = GalleryAlbum::orderBy('title')->get();

        return view('admin.gallery_images.edit', compact('image', 'albums'));
    }

    public function update(Request $request, $id)
    {
        $image = GalleryImage::findOrFail($id);

        $data = $request->validate([
            'album_id' => ['required', 'integer', 'exists:gallery_albums,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image_path')) {
            if (!empty($image->image_path)) {
                $old = base_path($image->image_path);
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $album = GalleryAlbum::findOrFail($data['album_id']);
            $folder = base_path('img/gallery/album_' . $album->id);

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $filename = uniqid('photo_') . '.' . $request->file('image_path')->extension();
            $request->file('image_path')->move($folder, $filename);

            $data['image_path'] = 'img/gallery/album_' . $album->id . '/' . $filename;
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;

        $image->update($data);

        return redirect()->route('admin.gallery.images')->with('success', 'Fotogrāfija atjaunināta');
    }

    public function destroy($id)
    {
        $image = GalleryImage::findOrFail($id);

        if (!empty($image->image_path)) {
            $path = base_path($image->image_path);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $image->delete();

        return redirect()->route('admin.gallery.images')->with('success', 'Fotogrāfija izdzēsta');
    }
}