<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Валидация
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:1000',
        ]);
    // Проверка на существующий отзыв
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Вы уже оставляли отзыв на этот товар.');
        }

        // Создание отзыва
        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'new',
        ]);

        return redirect()->back()->with('success', 'Спасибо за отзыв! Он будет опубликован после проверки модератором.');
    }

    public function myReviews()
    {
        $reviews = Review::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('my-reviews', compact('reviews'));
    }
}
