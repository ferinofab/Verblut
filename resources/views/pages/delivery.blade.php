@extends('layouts.app')

@section('title', 'Доставка и оплата')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Заголовок --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">Доставка и оплата</h1>
                    <div class="divider mx-auto bg-primary" style="width: 80px; height: 3px;"></div>
                </div>

                {{-- Доставка --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h2 class="h3 text-primary mb-0">
                            <i class="bi bi-truck me-2"></i> Доставка
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Самовывоз</h5>
                                        <p class="text-muted small mb-0">
                                            г. Москва, ул. Примерная, д. 123<br>
                                            Работаем: Пн-Пт с 10:00 до 20:00
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-box-seam-fill text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Курьерская доставка</h5>
                                        <p class="text-muted small mb-0">
                                            Доставка по Москве в день заказа<br>
                                            Стоимость: от 300 ₽
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-send-fill text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Почта России</h5>
                                        <p class="text-muted small mb-0">
                                            Срок доставки: 5-14 дней<br>
                                            Стоимость: от 250 ₽
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-building-fill text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">СДЭК / Boxberry</h5>
                                        <p class="text-muted small mb-0">
                                            Доставка до пункта выдачи или курьером<br>
                                            Стоимость по тарифам перевозчика
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Бесплатная доставка</strong> при заказе от 2 000 ₽
                        </div>
                    </div>
                </div>

                {{-- Оплата --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h2 class="h3 text-primary mb-0">
                            <i class="bi bi-credit-card me-2"></i> Оплата
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-credit-card-fill text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Банковской картой онлайн</h5>
                                        <p class="text-muted small mb-0">
                                            Visa, Mastercard, МИР<br>
                                            Безопасные платежи через защищённый шлюз
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-cash-stack fs-4 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Наличными курьеру</h5>
                                        <p class="text-muted small mb-0">
                                            Только для курьерской доставки по Москве<br>
                                            Оплата при получении
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-receipt fs-4 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">Безналичный расчёт</h5>
                                        <p class="text-muted small mb-0">
                                            Для юридических лиц и ИП<br>
                                            Выставляем счёт с НДС
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-qr-code fs-4 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold">СБП (Система быстрых платежей)</h5>
                                        <p class="text-muted small mb-0">
                                            Оплата по QR-коду через мобильное приложение банка
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
