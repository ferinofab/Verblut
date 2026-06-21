@extends('layouts.app')

@section('title', 'Возврат товара')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Заголовок --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">Возврат товара</h1>
                    <div class="divider mx-auto bg-primary" style="width: 80px; height: 3px;"></div>
                </div>

                {{-- Условия возврата --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-5">
                        <h2 class="h4 text-primary mb-4">
                            <i class="bi bi-arrow-return-left me-2"></i> Условия возврата
                        </h2>

                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Внимание!</strong> Возврат товара надлежащего качества возможен в течение 14 дней с момента получения.
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Товар подлежит возврату:</h5>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Товар не был в употреблении
                            </li>
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Сохранены товарный вид, фабричные ярлыки и упаковка
                            </li>
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Имеются все документы (чек, гарантийный талон)
                            </li>
                        </ul>

                        <h5 class="fw-bold mt-4 mb-3">Товар НЕ подлежит возврату:</h5>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                Химические средства (шампуни, полироли, пасты) — после вскрытия упаковки
                            </li>
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                Расходные материалы (полировальные круги, губки, салфетки)
                            </li>
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                Товары с истекшим сроком годности (если не были использованы)
                            </li>
                            <li class="list-group-item bg-transparent">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                Товары, заказанные индивидуально под клиента
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Процедура возврата --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-5">
                        <h2 class="h4 text-primary mb-4">
                            <i class="bi bi-steps me-2"></i> Как оформить возврат
                        </h2>

                        <div class="row g-4">
                            <div class="col-md-3 text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fs-3 fw-bold text-primary">1</span>
                                </div>
                                <p class="mb-0">Свяжитесь с нами по телефону или email</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fs-3 fw-bold text-primary">2</span>
                                </div>
                                <p class="mb-0">Заполните заявление на возврат</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fs-3 fw-bold text-primary">3</span>
                                </div>
                                <p class="mb-0">Отправьте товар удобным способом</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fs-3 fw-bold text-primary">4</span>
                                </div>
                                <p class="mb-0">Получите деньги обратно на карту или счёт</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Сроки возврата --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h4 text-primary mb-4">
                            <i class="bi bi-clock-history me-2"></i> Сроки возврата денег
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>Способ оплаты</th>
                                    <th>Срок возврата</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Банковская карта онлайн</td>
                                    <td>3-10 рабочих дней</td>
                                </tr>
                                <tr>
                                    <td>Наличными курьеру</td>
                                    <td>В день возврата товара</td>
                                </tr>
                                <tr>
                                    <td>Безналичный расчёт (юр. лица)</td>
                                    <td>5-15 рабочих дней</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-secondary mt-4 mb-0">
                            <i class="bi bi-envelope-fill me-2"></i>
                            <strong>Контакты для возврата:</strong> return@mirhimii.ru | +7 (800) 123-45-67
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
