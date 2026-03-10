<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $query = News::query()->with('category');

        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        if ($from) {
            $query->whereDate('publicets_datums', '>=', $from);
        }

        if ($to) {
            $query->whereDate('publicets_datums', '<=', $to);
        }

        $news = $query
            ->orderByDesc('publicets_datums')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.ieraksti', compact('news', 'q', 'from', 'to'));
    }
}