@extends('layouts.app')

@section('title', $product->name . ' - Мир Химии')

@section('content')
    <style>
        .thumb {
            transition: all 0.2s ease;
            opacity: 0.8;
        }
        .thumb:hover {
            opacity: 1;
            transform: scale(1.02);
        }
    </style>
    <div class="container py-4">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('catalog.category', $product->category->slug) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 50) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Галерея изображений -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="p-3 text-center bg-light">
                        {{-- Главное изображение --}}
                        <img src="{{ asset($product->mainImage->image_path ??
                            $product->images->first()?->image_path ??
                            $product->image_url ??
                            'https://placehold.co/600x500/e9ecef/495057?text=' . urlencode($product->name)) }}"
                             id="mainImage"
                             class="img-fluid rounded"
                             style="max-height: 400px; width: auto; object-fit: contain;"
                             alt="{{ $product->name }}">
                    </div>

                    {{-- Миниатюры --}}
                    @if($product->images->count())
                        <div class="row mt-3 g-2 p-3">
                            @foreach($product->images as $image)
                                <div class="col-3">
                                    <img src="{{ $image->image_path }}"
{{--                                    {{ $image->is_main ? 'border-primary border-2' : ''--}}
                                    class="img-fluid rounded border p-1 thumb  }}"
                                         style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"
                                         alt="{{ $product->name }}"
                                         onclick="changeMainImage('{{ $image->image_path }}', this)">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>


{{--            <div class="col-md-6 mb-4">--}}
{{--                <div class="card shadow-sm border-0 overflow-hidden">--}}
{{--                    <div class="p-3 text-center bg-light">--}}
{{--                        <img src="{{ $product->image_url ?? 'https://placehold.co/600x500/e9ecef/495057?text=' . urlencode($product->name) }}"--}}
{{--                             id="mainImage"--}}
{{--                             class="img-fluid rounded"--}}
{{--                             style="max-height: 400px; width: auto; object-fit: contain;"--}}
{{--                             alt="{{ $product->name }}">--}}
{{--                    </div>--}}
{{--                    <div class="row mt-3 g-2 p-3">--}}
{{--                        <div class="col-3">--}}
{{--                            <img src="{{ $product->image_url ?? 'https://placehold.co/100x100/e9ecef/495057?text=Фото+1' }}"--}}
{{--                                 class="img-fluid rounded border p-1 thumb"--}}
{{--                                 style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"--}}
{{--                                 alt="Миниатюра 1">--}}
{{--                        </div>--}}
{{--                        <div class="col-3">--}}
{{--                            <img src="https://placehold.co/100x100/e9ecef/495057?text=Фото+2"--}}
{{--                                 class="img-fluid rounded border p-1 thumb"--}}
{{--                                 style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"--}}
{{--                                 alt="Миниатюра 2">--}}
{{--                        </div>--}}
{{--                        <div class="col-3">--}}
{{--                            <img src="https://placehold.co/100x100/e9ecef/495057?text=Фото+3"--}}
{{--                                 class="img-fluid rounded border p-1 thumb"--}}
{{--                                 style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"--}}
{{--                                 alt="Миниатюра 3">--}}
{{--                        </div>--}}
{{--                        <div class="col-3">--}}
{{--                            <img src="https://placehold.co/100x100/e9ecef/495057?text=Фото+4"--}}
{{--                                 class="img-fluid rounded border p-1 thumb"--}}
{{--                                 style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"--}}
{{--                                 alt="Миниатюра 4">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Informații despre produs -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-4">
                    <div class="mb-3">
                        @if($product->amount > 0)
                            <span class="badge bg-success mb-2 px-3 py-2">
                            <i class="bi bi-check-circle"></i> В наличии ({{ $product->amount }} шт.)
                        </span>
                        @else
                            <span class="badge bg-danger mb-2 px-3 py-2">
                            <i class="bi bi-x-circle"></i> Нет в наличии
                        </span>
                        @endif
                        <h1 class="display-6 fw-bold">{{ $product->name }}</h1>
                        <div class="mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                            <span class="text-muted ms-2">156 отзывов</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="h1 text-primary mb-2">{{ number_format($product->price, 2) }} ₽</div>
                        @if($product->price > 500)
                            <div class="text-muted">
                                <span class="text-decoration-line-through">{{ number_format($product->price * 1.2, 2) }} ₽</span>
                                <span class="text-success ms-2">Экономия {{ number_format($product->price * 0.2, 2) }} ₽</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Количество:</h5>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="input-group" style="width: 150px;">
                                <button class="btn btn-secondary" type="button" id="btnMinus" style="border-radius: 8px 0 0 8px; padding: 0.375rem 0.75rem;">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" id="quantity" class="form-control text-center fw-semibold" value="1" min="1" max="{{ $product->amount }}" style="border-left: 0; border-right: 0; padding: 0; font-size: 1rem; width: 50px;">
                                <button class="btn btn-secondary" type="button" id="btnPlus" style="border-radius: 0 8px 8px 0; padding: 0.375rem 0.75rem;">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <span class="text-muted small">Макс. {{ $product->amount }} шт.</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex mb-4">
                        @if($product->amount > 0)
                            <button class="btn btn-primary btn-lg flex-grow-1" id="addToCartBtn">
                                <i class="bi bi-cart-plus"></i> Добавить в корзину
                            </button>
                            <button class="btn btn-success btn-lg flex-grow-1" id="buyNowBtn">
                                <i class="bi bi-lightning-charge"></i> Купить сейчас
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg flex-grow-1" disabled>
                                <i class="bi bi-x-circle"></i> Нет в наличии
                            </button>
                        @endif
                        <button class="btn btn-outline-danger btn-lg" id="addToWishlist">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <!-- Характеристики -->
                    <div class="card mb-3 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Характеристики</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td style="width: 40%;" class="text-muted">Артикул:</td>
                                    <td><strong>{{ $product->sku ?? '—' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Категория:</td>
                                    <td>
                                        <a href="{{ route('catalog.category', $product->category->slug ?? '') }}" class="text-decoration-none">
                                            {{ $product->category->name ?? 'Без категории' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Бренд:</td>
                                    <td><strong>MoySklad Auto</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Вес/Объем:</td>
                                    <td><strong>1 л / 1 кг</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Доставка -->
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-truck"></i> <strong>Бесплатная доставка</strong> при заказе от 2000 ₽
                    </div>
                </div>
            </div>
        </div>

        <!-- Табы с дополнительной информацией -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                    <i class="bi bi-file-text"></i> Описание
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">
                                    <i class="bi bi-table"></i> Характеристики
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                    <i class="bi bi-chat-dots"></i> Отзывы (156)
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <h4 class="mb-3">Описание товара</h4>
                                <p class="lead">{{ $product->description ?: 'Описание товара временно отсутствует. Информация будет добавлена позже.' }}</p>
                                <h5 class="mt-4">Преимущества:</h5>
                                <ul>
                                    <li>Высокое качество</li>
                                    <li>Доступная цена</li>
                                    <li>Быстрая доставка</li>
                                    <li>Гарантия качества</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="specs" role="tabpanel">
                                <h4 class="mb-3">Полные характеристики</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%;" class="bg-light">Производитель</th>
                                        <td>MoySklad Auto</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Страна производства</th>
                                        <td>Россия</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Объем/вес</th>
                                        <td>1 л / 1 кг</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Тип средства</th>
                                        <td>Универсальный</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Применение</th>
                                        <td>Для всех типов автомобилей</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade show" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h4 class="mb-0">Отзывы покупателей</h4>
                                        @if($product->approvedReviews()->count() > 0)
                                            <div class="mt-2">
                                                <span class="fw-bold">{{ number_format($product->averageRating(), 1) }}/5</span>
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($product->averageRating()))
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif($i - 0.5 <= $product->averageRating())
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                                <span class="text-muted ms-2">({{ $product->approvedReviews()->count() }} отзывов)</span>
                                            </div>
                                        @endif
                                    </div>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                        <i class="bi bi-pencil"></i> Написать отзыв
                                    </button>
                                </div>

                                <div class="reviews-list">
                                    @forelse($product->approvedReviews()->with('user')->latest()->get() as $review)
                                        <div class="d-flex gap-3 mb-3 p-3 border rounded">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-person-circle fs-1 text-secondary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ $review->user->name }}</strong>
                                                        <div class="mb-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                @else
                                                                    <i class="bi bi-star text-warning"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center p-5 border rounded bg-light">
                                            <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                                            <p class="mt-3 mb-0">Пока нет отзывов. Будьте первым, кто оставит отзыв!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Похожие товары -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4 fw-bold">Похожие товары</h3>
                </div>
                @foreach($relatedProducts as $related)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card product-card h-100 shadow-sm">
                            <img src="{{ $related->image_url ?? 'https://placehold.co/300x200/e9ecef/495057?text=' . urlencode($related->name) }}"
                                 class="card-img-top product-img"
                                 style="height: 150px; object-fit: contain; padding: 1rem;"
                                 alt="{{ $related->name }}">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ Str::limit($related->name, 35) }}</h6>
                                <p class="card-text text-primary fw-bold">{{ number_format($related->price, 2) }} ₽</p>
                                <a href="{{ route('product', $related->id) }}" class="btn btn-sm btn-outline-primary">
                                    Смотреть
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Модальное окно для отзыва -->
    <!-- Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">Написать отзыв</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Оценка --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Оценка <span class="text-danger">*</span></label>
                            <div class="rating">
                                <i class="bi bi-star fs-3" data-rating="1" style="cursor: pointer;"></i>
                                <i class="bi bi-star fs-3" data-rating="2" style="cursor: pointer;"></i>
                                <i class="bi bi-star fs-3" data-rating="3" style="cursor: pointer;"></i>
                                <i class="bi bi-star fs-3" data-rating="4" style="cursor: pointer;"></i>
                                <i class="bi bi-star fs-3" data-rating="5" style="cursor: pointer;"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" required>
                            <div class="invalid-feedback" id="ratingError">Пожалуйста, выберите оценку</div>
                        </div>

                        {{-- Имя пользователя (автоматически из Auth) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ваше имя</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name??null }}" disabled>
                            <small class="text-muted">Имя берется автоматически из вашего аккаунта</small>
                        </div>

                        {{-- Текст отзыва --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ваш отзыв <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="comment" rows="4" required placeholder="Поделитесь своим опытом использования товара..."></textarea>
                            <div class="invalid-feedback" id="commentError">Пожалуйста, напишите текст отзыва (минимум 3 символа)</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Переключение главного изображения при клике на миниатюру
        document.querySelectorAll('.thumb').forEach(thumb => {
            thumb.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
            });
        });

        // Увеличение/уменьшение количества
        const quantityInput = document.getElementById('quantity');
        const btnMinus = document.getElementById('btnMinus');
        const btnPlus = document.getElementById('btnPlus');
        const maxStock = {{ $product->amount }};

        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                let val = parseInt(quantityInput.value);
                if (val > 1) quantityInput.value = val - 1;
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                let val = parseInt(quantityInput.value);
                if (val < maxStock) quantityInput.value = val + 1;
            });
        }

        // Добавление в корзину (AJAX)
        document.getElementById('addToCartBtn')?.addEventListener('click', function() {
            const quantity = quantityInput.value;

            fetch('/cart/add-ajax/{{ $product->id }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        updateCartCount(data.cart_count);
                    } else if (data.error) {
                        showNotification(data.error, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Ошибка при добавлении в корзину', 'danger');
                });
        });

        // Покупка в один клик (простой способ без AJAX)
        document.getElementById('buyNowBtn')?.addEventListener('click', function() {
            const quantity = quantityInput.value;

            // Создаем форму и отправляем
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/cart/buy-now/{{ $product->id }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const quantityInputField = document.createElement('input');
            quantityInputField.type = 'hidden';
            quantityInputField.name = 'quantity';
            quantityInputField.value = quantity;

            form.appendChild(csrfInput);
            form.appendChild(quantityInputField);
            document.body.appendChild(form);
            form.submit();
        });

        // Обновление счетчика корзины в шапке
        function updateCartCount(count) {
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = count;
                if (count > 0) {
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        // Уведомление
        function showNotification(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            document.body.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 3000);
        }

        // Рейтинг в модальном окне
        document.querySelectorAll('.rating i').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('ratingValue').value = rating;
                document.querySelectorAll('.rating i').forEach((s, i) => {
                    s.className = i < rating ? 'bi bi-star-fill text-warning fs-3' : 'bi bi-star fs-3';
                });
            });
        });

    </script>
    <script>
        function changeMainImage(src, element) {
            // Меняем главное изображение
            document.getElementById('mainImage').src = src;

            // Убираем выделение со всех миниатюр
            document.querySelectorAll('.thumb').forEach(thumb => {
                thumb.classList.remove('border-primary', 'border-2');
            });

            // Выделяем текущую миниатюру
            element.classList.add('border-primary', 'border-2');
        }
    </script>
@endpush
