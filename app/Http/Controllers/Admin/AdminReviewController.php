<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'new');

        $reviews = Review::with(['user', 'product'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        $counts = [
            'new' => Review::where('status', 'new')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'total' => Review::count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'counts', 'status'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:new,approved,rejected'
        ]);

        $oldStatus = $review->status;
        $review->status = $request->status;
        $review->save();

        $message = match($request->status) {
            'approved' => 'Отзыв одобрен и опубликован',
            'rejected' => 'Отзыв отклонен',
            default => 'Статус изменен на "Новый"',
        };

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Отзыв удален');
    }
}
