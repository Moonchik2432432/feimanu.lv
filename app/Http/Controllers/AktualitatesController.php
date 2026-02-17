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

        // Получаем текст поиска из URL
        $q = trim($request->query('q', ''));

        // Запрос к таблице ieraksts
        $news = Ieraksts::with('kategorija') 
            ->where('status', 'published')  
            ->when($q !== '', function ($query) use ($q) {
                // Если есть поиск — фильтруем
                $query->where(function ($qq) use ($q) {
                    $qq->where('nosaukums', 'like', "%{$q}%") 
                       ->orWhere('saturs', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('publicets_datums') // сортировка по дате 
            ->paginate(2)               
            ->withQueryString();              // сохраняем ?q= при переключении страниц

        return view('aktualitates.index', compact('categories', 'news', 'q'));
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
        // Ищем новость по ID, только если она опубликована
        $post = Ieraksts::with('kategorija')
            ->where('status', 'published')
            ->findOrFail($id); // если не найдена — ошибка 404

        return view('aktualitates.show', compact('post'));
    }
}
