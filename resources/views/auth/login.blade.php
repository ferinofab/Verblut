@extends('layouts.app')

@section('title', 'Вход - Мир Химии')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow border-0">
                    <div class="card-header bg-white text-center pt-4 pb-0 border-0">
                        <h3 class="mb-0">Вход в аккаунт</h3>
                        <p class="text-muted">Добро пожаловать обратно!</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Логин или Email -->
                            <div class="mb-3">
                                <label for="login" class="form-label">
                                    <i class="bi bi-person-badge"></i> Логин или Email
                                </label>
                                <input type="text"
                                       class="form-control @error('login') is-invalid @enderror"
                                       id="login"
                                       name="login"
                                       value="{{ old('login') }}"
                                       required
                                       autofocus
                                       placeholder="Введите ваш логин или email">
                                @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Пароль -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Пароль
                                </label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Запомнить меня -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Запомнить меня</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> Войти
                            </button>

                            <div class="text-center mb-3">
                                <a href="#" class="text-decoration-none">Забыли пароль?</a>
                            </div>

                            <hr>

                            <div class="text-center">
                                <span class="text-muted">Нет аккаунта?</span>
                                <a href="{{ route('register') }}" class="text-decoration-none">Зарегистрироваться</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
