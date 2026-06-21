@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>

        {{-- Общий рейтинг --}}
        <div class="average-rating mb-4">
            <strong>Общий рейтинг:</strong>
            <span class="rating-stars">
            @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating))
                        ★
                    @elseif($i - 0.5 <= $averageRating)
                        ⭒
                    @else
                        ☆
                    @endif
                @endfor
        </span>
            <span class="rating-value">{{ number_format($averageRating, 1) }}/5</span>
            <span class="reviews-count">({{ $product->approvedReviews()->count() }} отзывов)</span>
        </div>

        {{-- Форма добавления отзыва --}}
        @auth
            <div class="card mb-4">
                <div class="card-header">Оставить отзыв</div>
                <div class="card-body">
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label class="form-label">Оценка</label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <label>
                                        <input type="radio" name="rating" value="{{ $i }}" required>
                                        {{ $i }} ★
                                    </label>
                                @endfor
                            </div>
                            @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Отзыв</label>
                            <textarea name="comment" class="form-control" rows="4" required>{{ old('comment') }}</textarea>
                            @error('comment') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Отправить на модерацию</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}">Авторизуйтесь</a>, чтобы оставить отзыв
            </div>
        @endauth

        {{-- Список отзывов --}}
        <div class="reviews-list">
            <h3>Отзывы покупателей</h3>

            @forelse($reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $review->user->login() }}</strong>
                                <div class="rating-stars text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d.m.Y') }}</small>
                        </div>
                        <p class="mt-2">{{ $review->comment }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary">Пока нет отзывов. Будьте первым!</div>
            @endforelse

            {{ $reviews->links() }}
        </div>
    </div>
@endsection
