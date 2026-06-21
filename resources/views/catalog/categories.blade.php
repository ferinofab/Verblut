@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - Мир Химии' : 'Категории - Мир Химии')

@section('content')
    <div class="container py-4">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                @if(isset($category))
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                @else
                    <li class="breadcrumb-item active">Категории</li>
                @endif
            </ol>
        </nav>

        <h1 class="mb-4 text-center">
            @if(isset($category))
                {{ $category->name }}
            @else
                Все категории
            @endif
        </h1>

        @if(isset($categories) && $categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $cat)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('catalog.category', $cat->slug) }}" class="text-decoration-none">
                            <div class="card category-card h-100 text-center shadow-sm border-0">
                                <div class="card-body p-4">
                                    <i class="bi bi-folder-fill fs-1 text-primary"></i>
                                    <h5 class="mt-3 mb-2">{{ $cat->name }}</h5>
                                    <small class="text-muted">{{ $cat->products->count() }} товаров</small>
                                    @if($cat->children->count() > 0)
                                        <div class="mt-2">
                                            <span class="badge bg-info">{{ $cat->children->count() }} подкатегорий</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-folder-x display-1 text-muted"></i>
                <h3 class="mt-3">Категории не найдены</h3>
                <p class="text-muted">В данном разделе пока нет категорий</p>
                <a href="{{ route('catalog') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left"></i> Вернуться в каталог
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .category-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 12px;
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            background-color: #0d6efd;
        }

        .category-card:hover .text-primary {
            color: white !important;
        }

        .category-card:hover h5,
        .category-card:hover .text-muted,
        .category-card:hover .badge {
            color: white !important;
        }

        .category-card:hover .badge {
            background-color: white !important;
            color: #0d6efd !important;
        }
    </style>
@endpush
