<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function noCategory()
    {
        if (!auth()->user()->is_admin??null) {
            abort(403);
        }

        $products = Product::whereNull('category_id')->paginate(20);
        $categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->get();
        return view('admin.no-category', compact('products', 'categories'));
    }

    public function withCategory()
    {
        if (!auth()->user()->is_admin??null) {
            abort(403);
        }

        $products = Product::whereNotNull('category_id')->with('category')->paginate(20);
        $categories = Category::all();
        return view('admin.with-category', compact('products', 'categories'));
    }

    public function updateCategory(Request $request, $id)
    {
        if (!auth()->user()->is_admin??null) {
            abort(403);
        }

        $product = Product::findOrFail($id);
        $product->update(['category_id' => $request->category_id ?: null]);
        return back()->with('success', 'Категория обновлена');
    }

    public function bulkUpdate(Request $request)
    {
        if (!auth()->user()->is_admin??null) {
            abort(403);
        }

        $request->validate([
            'product_ids' => 'required|array',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::whereIn('id', $request->product_ids)->update(['category_id' => $request->category_id]);

        return back()->with('success', 'Категории назначены');
    }
}
