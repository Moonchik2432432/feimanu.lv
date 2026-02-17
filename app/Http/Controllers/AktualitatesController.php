<?php

namespace App\Http\Controllers;

use App\Models\Ieraksts;    
use App\Models\Kategorija;   
use Illuminate\Http\Request; 

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
}
