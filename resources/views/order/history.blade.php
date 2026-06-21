@extends('layouts.app')

@section('title', 'История заказов')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">История заказов</h1>

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
            {{ $orders->links() }}
        @else
            <p>У вас пока нет заказов</p>
        @endif
    </div>
@endsection
