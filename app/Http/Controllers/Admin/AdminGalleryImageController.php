<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminGalleryImageController extends Controller
{
    public function index(Request $request)
    {
        $albumId = $request->query('album_id');
        $from = $request->query('from');
        $to = $request->query('to');
    
        if ($from && $to && $from > $to) {
            return back()->with('error', 'Datums "No" nevar būt lielāks par datumu "Līdz".');
        }
    
        $albums = GalleryAlbum::orderBy('title')->get();
    
        $images = GalleryImage::with('album')
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
            ->paginate(15) 
            ->withQueryString();
    
        return view('admin.gallery_images.index', compact(
            'images',
            'albums',
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
        $data = $request->validate(
            [
                'album_id' => ['required', 'integer', 'exists:gallery_albums,id'],
                'images' => ['required'],
                'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            [
                'album_id.required' => 'Lūdzu, izvēlieties albumu.',
                'album_id.integer' => 'Albums nav derīgs.',
                'album_id.exists' => 'Izvēlētais albums nepastāv.',
    
                'images.required' => 'Lūdzu, izvēlieties fotogrāfijas.',
                'images.*.image' => 'Failam jābūt attēlam.',
                'images.*.mimes' => 'Attēlam jābūt JPG, JPEG, PNG vai WEBP formātā.',
                'images.*.max' => 'Attēla izmērs nedrīkst pārsniegt 4 MB.',
            ]
        );
    
        $album = GalleryAlbum::findOrFail($data['album_id']);
        $folder = base_path('img/gallery/album_' . $album->id);
    
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }
    
        foreach ($request->file('images') as $file) {
    
            $filename = uniqid('photo_') . '.' . $file->extension();
            $file->move($folder, $filename);
    
            $imagePath = 'img/gallery/album_' . $album->id . '/' . $filename;
    
            $image = GalleryImage::create([
                'album_id' => $album->id,
                'image_path' => $imagePath,
            ]);
    
            // 👉 первая картинка становится обложкой
            if (empty($album->cover_image)) {
                $album->update([
                    'cover_image' => $imagePath,
                ]);
            }
        }
    
        return redirect()->route('admin.gallery.images')->with('success', 'Fotogrāfijas pievienotas');
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

        $data = $request->validate(
            [
                'album_id' => ['required', 'integer', 'exists:gallery_albums,id'],
                'title' => ['nullable', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer'],
                'image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            [
                'album_id.required' => 'Lūdzu, izvēlieties albumu.',
                'album_id.integer' => 'Albums nav derīgs.',
                'album_id.exists' => 'Izvēlētais albums nepastāv.',

                'title.string' => 'Nosaukumam jābūt tekstam.',
                'title.max' => 'Nosaukums nedrīkst būt garāks par 255 simboliem.',

                'sort_order.integer' => 'Kārtošanas numuram jābūt skaitlim.',

                'image_path.image' => 'Failam jābūt attēlam.',
                'image_path.mimes' => 'Attēlam jābūt JPG, JPEG, PNG vai WEBP formātā.',
                'image_path.max' => 'Attēla izmērs nedrīkst pārsniegt 4 MB.',
            ]
        );

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
