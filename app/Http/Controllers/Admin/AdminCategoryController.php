<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = Category::query();

        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        $categories = $query
            ->orderBy('nosaukums')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.category.index', compact('categories', 'q'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:100', 'unique:kategorija,nosaukums'],
        ]);

        Category::create($data);

        return redirect()->route('admin.category')->with('success', 'Kategorija pievienota');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'nosaukums' => ['required', 'string', 'max:100', 'unique:kategorija,nosaukums,' . $category->kategorija_id . ',kategorija_id'],
        ]);

        $category->update($data);

        return redirect()->route('admin.category')->with('success', 'Kategorija atjaunināta');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.category')->with('success', 'Kategorija izdzēsta');
    }
}