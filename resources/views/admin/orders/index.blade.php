@extends('layouts.app')

@section('title', 'Управление заказами')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-box-seam text-primary"></i> Управление заказами
                        </h4>
                        <span class="badge bg-primary">{{ $orders->total() }} заказов</span>
                    </div>
                    <div class="card-body">
                        <!-- Форма фильтрации -->
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4" id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" name="search" class="form-control" placeholder="Поиск по заказам..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select" id="statusFilter">
                                        <option value="">Все статусы</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel"></i> Фильтр
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                                        <i class="bi bi-arrow-counterclockwise"></i> Сброс
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Статистика -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-clock-history text-warning fs-2"></i>
                                    <h5 class="mt-2">{{ $orders->total() }}</h5>
                                    <small class="text-muted">Всего заказов</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-hourglass-split text-info fs-2"></i>
                                    <h5 class="mt-2">{{ $orders->where('order_status_id', 1)->count() }}</h5>
                                    <small class="text-muted">Новые</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-truck text-primary fs-2"></i>
                                    <h5 class="mt-2">{{ $orders->where('order_status_id', 2)->count() }}</h5>
                                    <small class="text-muted">В обработке</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-check-circle text-success fs-2"></i>
                                    <h5 class="mt-2">{{ $orders->where('order_status_id', 4)->count() }}</h5>
                                    <small class="text-muted">Выполнено</small>
                                </div>
                            </div>
                        </div>

                        <!-- Таблица заказов -->
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>№ заказа</th>
                                        <th>Клиент</th>
                                        <th>Телефон</th>
                                        <th>Сумма</th>
                                        <th>Дата</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>#{{ $order->id }}</strong>
                                            </td>
                                            <td>
                                                <div>{{ $order->name }}</div>
                                                <small class="text-muted">{{ $order->email }}</small>
                                            </td>
                                            <td>{{ $order->phone }}</td>
                                            <td>
                                                <strong>{{ number_format($order->total_amount, 2) }} ₽</strong>
                                            </td>
                                            <td>
                                                <small>{{ $order->created_at->format('d.m.Y H:i') }}</small>
                                            </td>
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
                                                <span class="badge bg-{{ $statusColors[$order->order_status_id] ?? 'secondary' }}">
                                                    {{ $order->status->name ?? 'Неизвестно' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Пагинация -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Показано {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} из {{ $orders->total() }}
                                    </small>
                                </div>
                                <div>
                                    {{ $orders->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Заказы не найдены</h5>
                                <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальные окна для смены статуса -->
    @foreach($orders as $order)
        <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel{{ $order->id }}">Изменить статус заказа #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="status-update-form">
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
                                    <span class="badge bg-{{ $statusColors[$order->order_status_id] ?? 'secondary' }}">
                                {{ $order->status->name ?? 'Неизвестно' }}
                            </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="status_id{{ $order->id }}" class="form-label">Новый статус</label>
                                <select name="status_id" id="status_id{{ $order->id }}" class="form-select" required>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
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
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматическое обновление при изменении фильтра статуса
            const statusFilter = document.querySelector('select[name="status"]');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            // Обработка отправки формы смены статуса через AJAX
            document.querySelectorAll('.status-update-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const action = this.getAttribute('action');
                    const modal = this.closest('.modal');
                    const modalId = modal.getAttribute('id');

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

                                // Обновляем страницу для отображения изменений
                                location.reload();
                            } else {
                                alert('Ошибка: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Произошла ошибка при обновлении статуса');
                        });
                });
            });
        });
    </script>
@endpush
