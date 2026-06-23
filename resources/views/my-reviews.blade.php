@extends('layouts.app')

@section('title', 'Мои отзывы')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Мои отзывы</h1>
            <a href="{{ url('/') }}" class="btn btn-primary">На главную</a>
        </div>

        @if($reviews->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                <p class="mt-3">Вы еще не оставили ни одного отзыва</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Перейти к товарам</a>
            </div>
        @else
            <div class="row">
                @foreach($reviews as $review)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">
                                            <a href="{{ url('/product/' . $review->product_id) }}" class="text-decoration-none">
                                                {{ $review->product->name }}
                                            </a>
                                        </h5>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                            @endfor
                                        </div>
                                        <p class="card-text">{{ $review->comment }}</p>
                                    </div>
                                    <div class="text-end">
                                    <span class="badge bg-{{ $review->status == 'approved' ? 'success' : ($review->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ $review->status == 'approved' ? 'Одобрен' : ($review->status == 'rejected' ? 'Отклонен' : 'На модерации') }}
                                    </span>
                                        <div class="small text-muted mt-2">
                                            {{ $review->created_at->format('d.m.Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
