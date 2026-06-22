@php use App\Models\Category; @endphp
@extends('layouts.app')

@section('title', 'Каталог товаров - Мир Химии')
<style>
    /* Стили для фонового изображения */
    .product-image-bg {
        height: 260px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px);
        transition: transform 0.3s ease;
    }

    /* Эффект увеличения при наведении на карточку */
    .product-card:hover .product-image-bg {
        transform: scale(1.02);
    }

    /* Плавный переход для карточки */
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Заголовок товара */
    .product-title {
        min-height: 48px;
        font-size: 0.95rem;
        line-height: 1.4;
    }

    /* Затемнение для отсутствующего товара */
    .product-overlay {
        border-radius: inherit;
    }

    /* Адаптив для мобильных устройств */
    @media (max-width: 768px) {
        .product-image-bg {
            height: 200px;
        }

        .product-title {
            min-height: auto;
            font-size: 0.85rem;
        }

        .product-card {
            max-width: 100% !important;
        }
    }

    @media (max-width: 576px) {
        .product-image-bg {
            height: 180px;
        }
    }
</style>
@section('content')

    <div class="container py-4">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Каталог</li>
                @if(isset($category))
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                @endif
            </ol>
        </nav>

        <div class="row">
            <!-- Боковая панель с фильтрами -->
            <div class="col-lg-3 mb-4">
                <div class=" shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-funnel"></i> Фильтры
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('catalog') }}" id="filterForm">
                            <!-- Поиск по названию -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Поиск</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Название товара..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Категории -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Категории</label>
                                <div class="categories-list" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Все категории -->
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="category" value=""
                                               id="cat_all"
                                               {{ !request('category') ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                        <label class="form-check-label" for="cat_all">
                                            Все категории
                                        </label>
                                    </div>

                                    @foreach($parentCategories as $cat)
                                        <div class="mb-2">
                                            <!-- Родительская категория (просто текст) -->
                                            <div class="d-flex align-items-center">
                                                <span class="text-secondary">{{ $cat->name }}</span>

                                                <!-- Кнопка-переключатель для дочерних категорий -->
                                                @if($cat->children->count() > 0)
                                                    <a class="text-muted ms-2" data-bs-toggle="collapse"
                                                       href="#subcat_{{ $cat->id }}" role="button"
                                                       aria-expanded="false" aria-controls="subcat_{{ $cat->id }}">
                                                        <i class="bi bi-chevron-down"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Дочерние категории (выпадающий список) -->
                                            @if($cat->children->count() > 0)
                                                <div class="collapse ms-3 mt-1" id="subcat_{{ $cat->id }}">
                                                    @foreach($cat->children as $child)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                   name="category" value="{{ $child->id }}"
                                                                   id="cat_{{ $child->id }}"
                                                                   {{ request('category') == $child->id ? 'checked' : '' }}
                                                                   onchange="this.form.submit()">
                                                            <label class="form-check-label small" for="cat_{{ $child->id }}">
                                                                {{ $child->name }}
                                                                <small class="text-muted">({{ $child->products->count() }})</small>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Цена -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Цена (₽)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" class="form-control"
                                               placeholder="от" value="{{ request('min_price') }}"
                                               onchange="this.form.submit()">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" class="form-control"
                                               placeholder="до" value="{{ request('max_price') }}"
                                               onchange="this.form.submit()">
                                    </div>
                                </div>
                            </div>

                            <!-- Наличие -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="in_stock" value="1"
                                           id="in_stock"
                                           {{ request('in_stock') ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="in_stock">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        Только в наличии
                                    </label>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Применить
                                </button>
                                <a href="{{ route('catalog') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-repeat"></i> Сбросить
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Товары -->
            <div class="col-lg-9">
                <!-- Сортировка -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div>
                        <h2 class="mb-0">
                            @if(isset($category))
                                {{ $category->name }}
                            @else
                                Каталог товаров
                            @endif
                        </h2>
                        <p class="text-muted mb-0 mt-2">
                            Найдено: <strong>{{ $products->total() }}</strong> товаров
                        </p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <select class="form-select" id="sortSelect" style="min-width: 200px;">
                            <option value="" {{ !request('sort') ? 'selected' : '' }}>Сортировка</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена: по
                                возрастанию
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена: по
                                убыванию
                            </option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название:
                                А-Я
                            </option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Новинки</option>
                        </select>
                    </div>
                </div>

                <!-- Товары -->
                @if($products->count() > 0)
                    <div class="row g-4">
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                                    <div class="card product-card h-100 shadow-sm border-0 w-100"
                                         style="max-width: 380px; margin: 0 auto;">
                                        {{-- Верхняя часть с изображением как фон --}}
                                        <div class="position-relative product-image-bg"
                                             style="background-image: url('{{ asset($product->mainImage->image_path ??
                         $product->images->first()?->image_path ??
                         $product->image_url ??
                         '/storage/products/495057.svg') }}');">

                                            @if($product->amount <= 0)
                                                <div
                                                    class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center product-overlay">
                                                    <span class="badge bg-danger fs-6 px-3 py-2">Нет в наличии</span>
                                                </div>
                                            @endif

                                            @if($product->price < 500)
                                                <span
                                                    class="badge bg-danger position-absolute top-0 start-0 m-2 px-3 py-2 discount-badge">
                            <i class="bi bi-tag"></i> Скидка
                        </span>
                                            @endif
                                        </div>

                                        <div class="card-body text-center d-flex flex-column">
                                            <h6 class="card-title fw-bold product-title">
                                                <a href="{{ route('product', $product->id) }}"
                                                   class="text-decoration-none text-dark">
                                                    {{ Str::limit($product->name, 50) }}
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                {{ $product->category->name ?? 'Без категории' }}
                                            </p>
                                            <div class="mb-3">
                                                @if($product->price < 500)
                                                    <span class="text-decoration-line-through text-muted me-2">
                                                        {{ number_format($product->price * 1.2, 2) }} ₽
                                                    </span>
                                                    <span class="fs-5 fw-bold text-danger">
                                                        {{ number_format($product->price, 2) }} ₽
                                                    </span>
                                                @else
                                                    <span class="fs-5 fw-bold text-primary">
                                                        {{ number_format($product->price, 2) }} ₽
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="d-grid mt-auto">
                                                @if($product->amount > 0)
                                                    @if(Auth::user()->is_admin ?? false)
                                                        <div class="d-flex gap-2">
                                                            <a href="/admin/products/{{ $product->id }}/edit"
                                                               class="btn btn-primary btn-sm flex-grow-1">✏️
                                                                Изменить</a>
                                                            <a href="/admin/products/{{ $product->id }}/images"
                                                               class="btn btn-secondary btn-sm flex-grow-1">🖼️ Фото</a>
                                                        </div>
                                                    @else
                                                        <button class="btn btn-primary add-to-cart"
                                                                data-id="{{ $product->id }}">
                                                            <i class="bi bi-cart-plus"></i> В корзину
                                                        </button>
                                                    @endif
                                                @else
                                                    @if(Auth::user()->is_admin ?? false)
                                                        <div class="d-flex gap-2">
                                                            <a href="/admin/products/{{ $product->id }}/edit"
                                                               class="btn btn-primary btn-sm flex-grow-1">✏️
                                                                Изменить</a>
                                                            <a href="/admin/products/{{ $product->id }}/images"
                                                               class="btn btn-secondary btn-sm flex-grow-1">🖼️ Фото</a>
                                                        </div>
                                                        @else
                                                        <button class="btn btn-secondary" disabled>
                                                            <i class="bi bi-x-circle"></i> Нет в наличии
                                                        </button>
                                                    @endif

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Пагинация -->
                    <div class="d-flex flex-column align-items-center mt-4">
                        {{ $products->links('vendor.pagination.custom') }}
                        <span class="text-muted small mt-2">
        Отображено {{ $products->firstItem() }}–{{ $products->lastItem() }} из {{ $products->total() }} товаров
             </span>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-3">Товары не найдены</h3>
                        <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                        <a href="{{ route('catalog') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-repeat"></i> Сбросить фильтры
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('sortSelect')?.addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });

        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.dataset.id;
                fetch(`/cart/add-ajax/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({quantity: 1})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const alert = document.createElement('div');
                            alert.className = 'alert alert-success position-fixed top-0 end-0 m-3';
                            alert.style.zIndex = '9999';
                            alert.innerHTML = data.message;
                            document.body.appendChild(alert);
                            setTimeout(() => alert.remove(), 2000);

                            const badge = document.querySelector('.cart-badge');
                            if (badge) badge.textContent = data.cart_count;
                        }
                    });
            });
        });
    </script>
@endpush
