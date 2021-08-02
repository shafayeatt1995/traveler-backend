<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $categories = Category::latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function create(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
    }

    public function update(Category $category, Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
    }

    public function delete(Category $category)
    {
        $this->authorize('admin');
        $category->delete();
    }
}
