<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Показать все изображения товара
     */
    public function index(Product $product)
    {
        $images = $product->images()->get();
        return view('admin.products.images.index', compact('product', 'images'));
    }

    /**
     * Массовая загрузка изображений
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            // Сохраняем изображение
            $path = $image->store('products/' . $product->id, 'public');
            $fullPath = '/storage/' . $path;

            // Создаем запись в БД
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $fullPath,
                'is_main' => false
            ]);

            $uploadedImages[] = $productImage;
        }

        // Если это первые изображения для товара, делаем первое главным
        if ($product->images()->count() == count($uploadedImages)) {
            $uploadedImages[0]->update(['is_main' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Загружено ' . count($uploadedImages) . ' изображений',
            'images' => $uploadedImages
        ]);
    }

    /**
     * Установить главное изображение
     */
    public function setMain(Product $product, ProductImage $image)
    {
        // Снимаем флаг is_main со всех изображений товара
        $product->images()->update(['is_main' => false]);

        // Устанавливаем главное изображение
        $image->update(['is_main' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Главное изображение обновлено'
        ]);
    }

    /**
     * Удалить изображение
     */
    public function destroy(Product $product, ProductImage $image)
    {
        // Удаляем физический файл
        $publicPath = public_path($image->image_path);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }

        // Удаляем запись из БД
        $image->delete();

        // Если удалили главное изображение, назначаем новое
        if ($image->is_main && $product->images()->exists()) {
            $newMain = $product->images()->first();
            $newMain->update(['is_main' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Изображение удалено'
        ]);
    }
}
