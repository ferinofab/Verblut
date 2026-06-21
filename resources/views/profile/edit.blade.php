@extends('layouts.app')

@section('title', 'Настройки профиля')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person"></i> Мой профиль
                    </a>
                    <a href="{{ route('orders.history') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history"></i> Мои заказы
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-gear"></i> Настройки
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Настройки профиля</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Имя</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Телефон</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Адрес</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        </form>

                        <hr class="my-4">

                        <h5>Смена пароля</h5>
                        <form action="{{ route('profile.change-password') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Текущий пароль</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Новый пароль</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Подтверждение пароля</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-warning">Изменить пароль</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
