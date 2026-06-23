@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Навигация -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Заказы</a></li>
                        <li class="breadcrumb-item active">Заказ #{{ $order->id }}</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-0">
                            <i class="bi bi-box-seam text-primary"></i> Заказ #{{ $order->id }}
                        </h4>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="bi bi-arrow-repeat"></i> Сменить статус
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Информация о заказе -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Информация о заказе</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th style="width: 40%;">Номер заказа:</th>
                                                <td><strong>#{{ $order->id }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Дата создания:</th>
                                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Последнее обновление:</th>
                                                <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Статус:</th>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            1 => 'secondary',
                                                            2 => 'primary',
                                                            3 => 'warning',
                                                            4 => 'success',
                                                            5 => 'danger'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColors[$order->order_status_id] ?? 'secondary' }} fs-6">
                                                    {{ $order->status->name ?? 'Неизвестно' }}
                                                </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Способ доставки:</th>
                                                <td>
                                                    @switch($order->delivery_method)
                                                        @case('courier')
                                                            <i class="bi bi-truck"></i> Курьер
                                                            @break
                                                        @case('pickup')
                                                            <i class="bi bi-shop"></i> Самовывоз
                                                            @break
                                                        @case('post')
                                                            <i class="bi bi-envelope"></i> Почта
                                                            @break
                                                        @default
                                                            {{ $order->delivery_method }}
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Способ оплаты:</th>
                                                <td>
                                                    @switch($order->payment_method)
                                                        @case('card')
                                                            <i class="bi bi-credit-card"></i> Банковская карта
                                                            @break
                                                        @case('cash')
                                                            <i class="bi bi-cash"></i> Наличные
                                                            @break
                                                        @default
                                                            {{ $order->payment_method }}
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Сумма заказа:</th>
                                                <td><h5 class="text-success mb-0">{{ number_format($order->total_amount, 2) }} ₽</h5></td>
                                            </tr>
                                            <tr>
                                                <th>Комментарий:</th>
                                                <td>{{ $order->comment ?? 'Нет комментария' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Информация о клиенте -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-person"></i> Информация о клиенте</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th style="width: 40%;">Имя:</th>
                                                <td><strong>{{ $order->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Телефон:</th>
                                                <td>
                                                    <a href="tel:{{ $order->phone }}" class="text-decoration-none">
                                                        <i class="bi bi-telephone"></i> {{ $order->phone }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>
                                                    <a href="mailto:{{ $order->email }}" class="text-decoration-none">
                                                        <i class="bi bi-envelope"></i> {{ $order->email }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Адрес доставки:</th>
                                                <td>{{ $order->address }}</td>
                                            </tr>
                                            @if($order->city)
                                                <tr>
                                                    <th>Город:</th>
                                                    <td>{{ $order->city }}</td>
                                                </tr>
                                            @endif
                                            @if($order->postal_code)
                                                <tr>
                                                    <th>Индекс:</th>
                                                    <td>{{ $order->postal_code }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <!-- Действия с заказом -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-gear"></i> Действия</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                                                <i class="bi bi-arrow-repeat"></i> Изменить статус
                                            </button>
                                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Вернуться к списку
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Товары в заказе -->
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-cart"></i> Товары в заказе</h5>
                                    <span class="badge bg-primary">{{ $order->items->count() }} товаров</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th>№</th>
                                                <th>Товар</th>
                                                <th>Цена</th>
                                                <th>Кол-во</th>
                                                <th>Сумма</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->items as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($item->product)
                                                            <a href="{{ route('admin.products.show', $item->product) }}" class="text-decoration-none">
                                                                {{ $item->product_name }}
                                                            </a>
                                                            <br>
                                                            <small class="text-muted">Артикул: {{ $item->product->article ?? 'Нет' }}</small>
                                                        @else
                                                            {{ $item->product_name }}
                                                            <br>
                                                            <small class="text-muted text-danger">Товар удален</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($item->product_price, 2) }} ₽</td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td><strong>{{ number_format($item->total, 2) }} ₽</strong></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot class="table-active">
                                            <tr>
                                                <th colspan="4" class="text-end">Итого:</th>
                                                <th><h5 class="mb-0 text-success">{{ number_format($order->total_amount, 2) }} ₽</h5></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- История статусов (опционально) -->
                        @if(isset($order->statusHistory) && $order->statusHistory->count() > 0)
                            <div class="mt-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> История изменений статуса</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            @foreach($order->statusHistory as $history)
                                                <div class="d-flex mb-3">
                                                    <div class="flex-shrink-0">
                                                <span class="badge bg-{{ $statusColors[$history->status_id] ?? 'secondary' }} rounded-circle p-2">
                                                    <i class="bi bi-check2"></i>
                                                </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">{{ $history->status->name ?? 'Неизвестно' }}</h6>
                                                        <small class="text-muted">
                                                            {{ $history->created_at->format('d.m.Y H:i') }}
                                                            @if($history->user)
                                                                - {{ $history->user->name }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно смены статуса -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Изменить статус заказа #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" id="statusUpdateForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Текущий статус</label>
                            <p>
                                @php
                                    $statusColors = [
                                        1 => 'secondary',
                                        2 => 'primary',
                                        3 => 'warning',
                                        4 => 'success',
                                        5 => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->order_status_id] ?? 'secondary' }} fs-6">
                                {{ $order->status->name ?? 'Неизвестно' }}
                            </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="status_id" class="form-label">Новый статус</label>
                            <select name="status_id" id="status_id" class="form-select" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Комментарий к изменению (необязательно)</label>
                            <textarea name="comment" id="comment" class="form-control" rows="2" placeholder="Добавьте комментарий..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка отправки формы смены статуса
            const form = document.getElementById('statusUpdateForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const action = this.getAttribute('action');
                    const modal = document.getElementById('statusModal');

                    fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Закрываем модальное окно
                                const modalInstance = bootstrap.Modal.getInstance(modal);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }

                                // Показываем уведомление об успехе
                                showNotification('success', data.message);

                                // Обновляем страницу через 1 секунду
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showNotification('danger', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('danger', 'Произошла ошибка при обновлении статуса');
                        });
                });
            }

            // Функция для показа уведомлений
            function showNotification(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
                alertDiv.style.zIndex = '9999';
                alertDiv.style.minWidth = '300px';
                alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                document.body.appendChild(alertDiv);

                // Автоматическое скрытие через 5 секунд
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        });
    </script>
@endpush
