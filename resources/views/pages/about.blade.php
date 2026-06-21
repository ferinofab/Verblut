@extends('layouts.app')

@section('title', 'О компании')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Hero блок --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">О компании</h1>
                    <div class="divider mx-auto bg-primary" style="width: 80px; height: 3px;"></div>
                </div>

                {{-- Основная информация --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h2 class="h3 text-primary mb-4">Мир Химии</h2>
                        <p class="lead mb-4">
                            Интернет-магазин автомобильной химии и средств для ухода за автомобилем.
                        </p>
                        <p class="mb-4">
                            Мы предлагаем только качественные товары от проверенных производителей.
                            Наша миссия — сделать уход за автомобилем простым и доступным для каждого автовладельца.
                        </p>
                        <p class="mb-0">
                            В нашем ассортименте представлены шампуни, полироли, защитные покрытия,
                            средства для ухода за интерьером и экстерьером, а также профессиональный
                            инструмент для детейлинга. Мы тщательно отбираем каждую позицию, чтобы
                            вы могли быть уверены в результате.
                        </p>
                    </div>
                </div>

                {{-- Наши преимущества --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-shield-check fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Гарантия качества</h5>
                            <p class="text-muted small mb-0">
                                Только сертифицированные товары от официальных дистрибьюторов
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-truck fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Быстрая доставка</h5>
                            <p class="text-muted small mb-0">
                                Доставка по всей России в кратчайшие сроки
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-headset fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Поддержка 24/7</h5>
                            <p class="text-muted small mb-0">
                                Консультации специалистов по любым вопросам
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Наша команда --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h3 class="h4 text-center text-primary mb-4">Наша команда</h3>
                        <p class="text-center mb-0">
                            Мы — команда профессионалов, увлечённых своим делом. За нашими плечами
                            многолетний опыт в автомобильной химии и детейлинге. Мы знаем, как
                            добиться идеального результата, и готовы поделиться этими знаниями с вами.
                        </p>
                    </div>
                </div>

                {{-- Кнопка в каталог --}}
                <div class="text-center">
                    <a href="{{ route('catalog') }}" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Перейти в каталог
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
