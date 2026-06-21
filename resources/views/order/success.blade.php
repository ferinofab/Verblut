@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
    <div class="container py-5 text-center">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
        <h1 class="mt-3">Заказ успешно оформлен!</h1>
        <p>Номер заказа: <strong>{{ $order->order_number }}</strong></p>
        <a href="{{ route('catalog') }}" class="btn btn-primary">Вернуться в каталог</a>
    </div>
@endsection
