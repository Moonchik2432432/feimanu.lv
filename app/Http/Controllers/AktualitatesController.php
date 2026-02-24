<?php

namespace App\Http\Controllers;

use App\Models\Ieraksts;    
use App\Models\Kategorija;   
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\File;

class AktualitatesController extends Controller
{
// Главная страница новостей
public function index(Request $request)
{
    // Получаем все категории (для меню)
    $categories = Kategorija::orderBy('nosaukums')->get();

    // Поиск
    $q = trim($request->query('q', ''));

    // Даты фильтра
    $from = $request->query('from'); // от даты
    $to   = $request->query('to');   // до даты

    $news = Ieraksts::with('kategorija')
        ->where('status', 'published')

        // Поиск по тексту
        ->when($q !== '', function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('nosaukums', 'like', "%{$q}%")
                   ->orWhere('saturs', 'like', "%{$q}%");
            });
        })

        // Фильтр ОТ даты
        ->when($from, function ($query) use ($from) {
            $query->whereDate('publicets_datums', '>=', $from);
        })

        // Фильтр ДО даты
        ->when($to, function ($query) use ($to) {
            $query->whereDate('publicets_datums', '<=', $to);
        })

        ->orderByDesc('publicets_datums')
        ->paginate(5)
        ->withQueryString();

    return view('aktualitates.index', compact('categories', 'news', 'q', 'from', 'to'));
}



    // Страница новостей конкретной категории
    public function category(Request $request, $id)
    {
        // Получаем категории для меню
        $categories = Kategorija::orderBy('nosaukums')->get();

        // Получаем текст поиска
        $q = trim($request->query('q', ''));

        // Запрос новостей
        $news = Ieraksts::with('kategorija')
            ->where('status', 'published')   
            ->where('kategorija_id', $id)  
            ->when($q !== '', function ($query) use ($q) {
                // Если есть поиск — фильтр 
                $query->where(function ($qq) use ($q) {
                    $qq->where('nosaukums', 'like', "%{$q}%")
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('publicets_datums')
            ->paginate(5)                 
            ->withQueryString();

        return view('aktualitates.index', compact('categories', 'news', 'q'));
    }

    // Страница одной новости
    public function show($id)
    {
        // Ищем новость и коментарии по ID, только если она опубликована
        $post = Ieraksts::with(['kategorija', 'komentari.user'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('aktualitates.show', compact('post'));
    }

    // Форма создания новости
    public function create()
    {
        $categories = Kategorija::orderBy('nosaukums')->get();
        return view('aktualitates.create', compact('categories'));
    }

    // Сохранение новой новости
    public function store(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:255'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
        ]);

        // publicets_datums ставим только если published
        $data['publicets_datums'] = $data['status'] === 'published' ? now() : null;

        // загрузка картинки
        if ($request->hasFile('bilde')) {
            $dir = base_path('img/aktualitates');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $filename = uniqid('news_') . '.' . $request->file('bilde')->extension();
            $request->file('bilde')->move($dir, $filename);

            // В БД сохраняем относительный путь, чтобы asset() работал
            $data['bilde'] = 'img/aktualitates/' . $filename;
        }

        Ieraksts::create($data);

        return redirect()->route('aktualitates.index')->with('success', 'Aktualitāte izveidota!');
    }

    // Форма редактирования
    public function edit($id)
    {
        $post = Ieraksts::findOrFail($id);
        $categories = Kategorija::orderBy('nosaukums')->get();

        return view('aktualitates.edit', compact('post', 'categories'));
    }

    // Обновление новости
    public function update(Request $request, $id)
    {
        $post = Ieraksts::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:255'],
            'saturs' => ['required', 'string'],
            'kategorija_id' => ['required', 'integer', 'exists:kategorija,kategorija_id'],
            'bilde' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
        ]);

        // publicets_datums: если переводим в published и даты ещё нет — поставить now()
        if ($data['status'] === 'published' && empty($post->publicets_datums)) {
            $data['publicets_datums'] = now();
        }

        if ($request->hasFile('bilde')) {
            // удалить старую картинку
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

        return redirect()->route('aktualitates.index')->with('success', 'Aktualitāte atjaunināta!');
    }

    // Удаление новости
    public function destroy($id)
    {
        $post = Ieraksts::findOrFail($id);

        // удалить файл картинки
        if (!empty($post->bilde)) {
            $path = public_path($post->bilde);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return redirect()->route('aktualitates.index')->with('success', 'Aktualitāte dzēsta!');
    }
}
