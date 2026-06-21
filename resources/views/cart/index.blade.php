@extends('layouts.app')

@section('title', 'Корзина - Мир Химии')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Корзина</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="row">
                <!-- Левая колонка - таблица товаров -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Товар</th>
                                        <th style="width: 15%">Цена</th>
                                        <th style="width: 25%">Количество</th>
                                        <th style="width: 15%">Сумма</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart->items as $item)
                                        <tr data-item-id="{{ $item->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($item->product->main_image_url ??
                                                    $item->product->images->first()?->image_path ??
                                                    $item->product->image_url ??
                                                    '/storage/products/495057.svg') }}"
                                                         alt="{{ $item->product->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         class="rounded me-3">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">Артикул: {{ $item->product->sku ?? '—' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-nowrap">{{ number_format($item->price, 2) }} ₽</td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-minus" type="button">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number"
                                                           class="form-control text-center quantity"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           max="{{ $item->product->amount }}"
                                                           style="max-width: 50px;">
                                                    <button class="btn btn-outline-secondary btn-plus" type="button">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="item-total fw-bold text-nowrap">{{ number_format($item->quantity * $item->price, 2) }} ₽</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger btn-remove" title="Удалить">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Продолжить покупки
                        </a>
                    </div>
                </div>

                <!-- Правая колонка - итого -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Итого по корзине</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Товаров:</span>
                                <span class="fw-bold cart-count">{{ $cart->items->sum('quantity') }} шт.</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Общая сумма:</span>
                                <span class="fs-4 fw-bold text-primary cart-total">{{ number_format($total, 2) }} ₽</span>
                            </div>
                            <hr>
                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                    Оформить заказ <i class="bi bi-arrow-right"></i>
                                </a>
                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary w-100" onclick="return confirm('Очистить корзину?')">
                                        Очистить корзину
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3">Корзина пуста</h3>
                <p class="text-muted">Добавьте товары в корзину, чтобы оформить заказ</p>
                <a href="{{ route('catalog') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-cart-plus"></i> Перейти в каталог
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                let input = this.closest('.input-group').querySelector('.quantity');
                let newVal = Math.max(1, parseInt(input.value) - 1);
                updateQuantity(this, newVal);
            });
        });

        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                let input = this.closest('.input-group').querySelector('.quantity');
                let max = parseInt(input.max);
                let newVal = Math.min(max, parseInt(input.value) + 1);
                updateQuantity(this, newVal);
            });
        });

        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                let row = this.closest('tr');
                let itemId = row.dataset.itemId;

                fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            });
        });

        function updateQuantity(element, quantity) {
            let row = element.closest('tr');
            let itemId = row.dataset.itemId;

            fetch(`/cart/update/${itemId}/${quantity}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.querySelector('.item-total').textContent = data.item_total.toFixed(2) + ' ₽';
                        document.querySelector('.cart-total').textContent = data.cart_total.toFixed(2) + ' ₽';
                        document.querySelector('.cart-count').textContent = data.item_count + ' шт.';
                        row.querySelector('.quantity').value = quantity;
                    } else {
                        alert(data.error);
                    }
                });
        }
    </script>
@endpush
