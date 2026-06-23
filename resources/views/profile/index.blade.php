@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person"></i> Мой профиль
                    </a>
                    <a href="{{ route('orders.history') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history"></i> Мои заказы
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear"></i> Настройки
                    </a>
                    <a href="{{ route('my.reviews') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-chat-square-text"></i> Мои отзывы
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="bi bi-box-arrow-right"></i> Выйти
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Добро пожаловать, {{ $user->name }}!</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <i class="bi bi-truck fs-1 text-primary"></i>
                                    <h3 class="mt-2">{{ $ordersCount }}</h3>
                                    <p class="text-muted">Всего заказов</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <i class="bi bi-currency-ruble fs-1 text-success"></i>
                                    <h3 class="mt-2">{{ number_format($totalSpent, 0) }} ₽</h3>
                                    <p class="text-muted">Потрачено</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <i class="bi bi-calendar fs-1 text-info"></i>
                                    <h3 class="mt-2">{{ $user->created_at->format('d.m.Y') }}</h3>
                                    <p class="text-muted">Дата регистрации</p>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Последние заказы</h5>

                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>№ заказа</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                            <td>{{ number_format($order->total_amount, 2) }} ₽</td>
                                            <td><span class="badge bg-secondary">{{ $order->status->name ?? 'Новый' }}</span></td>
                                            <td><a href="{{ route('order.show', $order) }}" class="btn btn-sm btn-primary">Детали</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('orders.history') }}" class="btn btn-primary">Все заказы →</a>
                        @else
                            <p class="text-muted">У вас пока нет заказов</p>
                            <a href="{{ route('catalog') }}" class="btn btn-primary">Перейти в каталог</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
