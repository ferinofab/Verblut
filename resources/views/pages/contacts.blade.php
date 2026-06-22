@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Заголовок --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">Контакты</h1>
                    <div class="divider mx-auto bg-primary" style="width: 80px; height: 3px;"></div>
                    <p class="lead mt-3">Мы всегда на связи и готовы ответить на ваши вопросы</p>
                </div>

                <div class="row g-4">
                    {{-- Контактная информация --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="bi bi-info-circle me-2"></i> Свяжитесь с нами
                                </h3>

                                {{-- Телефон --}}
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-telephone-fill text-primary fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Телефон</h6>
                                            <a href="tel:+78001234567" class="text-decoration-none">8 (999) 315-99-02</a><br>
                                            <small class="text-muted">Бесплатно по России</small>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-envelope-fill text-primary fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Email</h6>
                                        <p class="mb-0">
                                            <a href="mailto:info@mirhimii.ru" class="text-decoration-none">infoMirHimii@gmail.com</a> — общие вопросы<br>
                                            <a href="mailto:orders@mirhimii.ru" class="text-decoration-none">ordersMirHimii@gmail.com</a> — заказы<br>
                                            <a href="mailto:wholesale@mirhimii.ru" class="text-decoration-none">optMirHimii@gmail.com</a> — опт
                                        </p>
                                    </div>
                                </div>

                                {{-- Адрес --}}
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Адрес</h6>
                                        <p class="mb-0">
                                            респ. Хакасия, г. Абакан,<br>
                                            ул Крылова 55Б<br>
                                            <small class="text-muted">Пн-Пт: 10:00 – 20:00</small>
                                        </p>
                                    </div>
                                </div>

                                {{-- Реквизиты (аккордеон) --}}
                                <div class="accordion" id="requisitesAccordion">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#requisitesCollapse">
                                                <i class="bi bi-bank me-2"></i> Реквизиты ИП Егорова В.Н.
                                            </button>
                                        </h2>
                                        <div id="requisitesCollapse" class="accordion-collapse collapse" data-bs-parent="#requisitesAccordion">
                                            <div class="accordion-body small">
                                                <p class="mb-1"><strong>ИНН:</strong> 123456789012</p>
                                                <p class="mb-1"><strong>ОГРНИП:</strong> 312345678901234</p>
                                                <p class="mb-1"><strong>Расчётный счёт:</strong> 40802810123456789012</p>
                                                <p class="mb-1"><strong>Банк:</strong> ПАО Сбербанк г. Москва</p>
                                                <p class="mb-1"><strong>БИК:</strong> 044525225</p>
                                                <p class="mb-0"><strong>Корр. счёт:</strong> 30101810400000000225</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Форма обратной связи + карта --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="bi bi-chat-dots me-2"></i> Напишите нам
                                </h3>

                                <form action="{{ route('contact.send') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Ваше имя *</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="subject" class="form-label">Тема</label>
                                            <select class="form-select" id="subject" name="subject">
                                                <option value="question">Вопрос о товаре</option>
                                                <option value="order">Вопрос о заказе</option>
                                                <option value="wholesale">Оптовое сотрудничество</option>
                                                <option value="other">Другое</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="message" class="form-label">Сообщение *</label>
                                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bi bi-send me-2"></i> Отправить сообщение
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Карта --}}
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="https://yandex.ru/map-widget/v1/?ll=91.443565,53.737548&z=18&pt=91.443565,53.737548,pm2rdm&text=Респ.%20Хакасия,%20г.%20Абакан,%20ул.%20Крылова%2055Б"
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
