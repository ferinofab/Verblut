@extends('layouts.app')

@section('title', 'Оформление заказа - Мир Химии')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Оформление заказа</h1>

        <div class="row">
            <div class="col-lg-7">
                <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                    @csrf

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-person"></i> Контактные данные</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ФИО *</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Телефон *</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Адрес доставки</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Город</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Почтовый индекс</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Адрес *</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                              rows="3" required>{{ old('address') }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-truck"></i> Доставка и оплата</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Способ доставки *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="delivery_courier"
                                           value="courier" checked>
                                    <label class="form-check-label" for="delivery_courier">
                                        Курьером (бесплатно при заказе от 2000 ₽)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="delivery_pickup" value="pickup">
                                    <label class="form-check-label" for="delivery_pickup">
                                        Самовывоз (г. Санкт-Петербург, ул. Примерная, д. 1)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="delivery_post" value="post">
                                    <label class="form-check-label" for="delivery_post">
                                        Почта России (по тарифам почты)
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Способ оплаты *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card"
                                           value="card" checked>
                                    <label class="form-check-label" for="payment_card">
                                        Банковской картой онлайн
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                                    <label class="form-check-label" for="payment_cash">
                                        Наличными при получении
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Комментарий к заказу</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Пожелания по доставке...">{{ old('comment') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-check-circle"></i> Подтвердить заказ
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-cart"></i> Ваш заказ</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-2">
                        <span>
                            {{ $item->product->name }}
                            <small class="text-muted">x {{ $item->quantity }}</small>
                        </span>
                                <span>{{ number_format($item->quantity * $item->price, 2) }} ₽</span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Товары:</span>
                            <span>{{ number_format($total, 2) }} ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Доставка:</span>
                            <span id="deliveryPrice">Бесплатно</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Итого:</strong>
                            <strong class="fs-4 text-primary" id="totalWithDelivery">{{ number_format($total, 2) }} ₽</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('input[name="delivery_method"]').forEach(radio => {
                radio.addEventListener('change', updateDeliveryPrice);
            });

            function updateDeliveryPrice() {
                let deliveryMethod = document.querySelector('input[name="delivery_method"]:checked').value;
                let total = {{ $total }};
                let deliveryPrice = 0;
                let deliveryText = '';

                if (deliveryMethod === 'courier') {
                    if (total >= 2000) {
                        deliveryText = 'Бесплатно';
                    } else {
                        deliveryPrice = 300;
                        deliveryText = '300 ₽';
                    }
                } else if (deliveryMethod === 'pickup') {
                    deliveryText = 'Бесплатно';
                } else if (deliveryMethod === 'post') {
                    deliveryPrice = 400;
                    deliveryText = '400 ₽';
                }

                document.getElementById('deliveryPrice').textContent = deliveryText;
                document.getElementById('totalWithDelivery').textContent = (total + deliveryPrice).toFixed(2) + ' ₽';
            }
        </script>
    @endpush
@endsection
