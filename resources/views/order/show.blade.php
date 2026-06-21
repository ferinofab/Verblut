@extends('layouts.app')

@section('title', 'Заказ #' . $order->order_number)

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Заказ #{{ $order->order_number }}</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Состав заказа</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->product_price, 2) }} ₽</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->total, 2) }} ₽</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Данные получателя</div>
                    <div class="card-body">
                        <p><strong>ФИО:</strong> {{ $order->name }}</p>
                        <p><strong>Телефон:</strong> {{ $order->phone }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Адрес:</strong> {{ $order->address }}</p>
                        <p><strong>Доставка:</strong> {{ $order->delivery_method }}</p>
                        <p><strong>Оплата:</strong> {{ $order->payment_method }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
