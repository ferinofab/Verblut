@php use Illuminate\Support\Facades\Auth; @endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            <i class="bi bi-droplet-fill text-primary"></i> Мир Химии
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Главная
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog*') ? 'active' : '' }}" href="{{ route('catalog') }}">
                        <i class="bi bi-grid"></i> Каталог
                    </a>
                </li>

                <!-- Категории с подменю -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-tags"></i> Категории
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        @php
                            $parentCategories = App\Models\Category::whereNull('parent_id')->get();
                        @endphp

                        @foreach($parentCategories as $cat)
                            @if($cat->children->count() > 0)
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="{{ route('catalog.category', $cat->slug) }}">
                                        {{ $cat->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($cat->children as $child)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('catalog.category', $child->slug) }}">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('catalog.category', $cat->slug) }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('catalog') }}">Все категории →</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="referenceDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-book"></i> Справочник
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('about') }}">О компании</a></li>
                        <li><a class="dropdown-item" href="{{ route('delivery') }}">Доставка и оплата</a></li>
                        <li><a class="dropdown-item" href="{{ route('return') }}">Возврат товара</a></li>
                        <li><a class="dropdown-item" href="{{ route('contacts') }}">Контакты</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Корзина и профиль -->
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart') }}">
                        <i class="bi bi-cart fs-5"></i>
                        @auth
                            @php
                                $cart = App\Models\Cart::where('user_id', Auth::id())->first();
                                $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        @endauth
                    </a>
                </li>

                <!-- АДМИН МЕНЮ (показывается только для админов) -->
                @auth
                    @if(Auth::user()->is_admin ?? false)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-shield-lock"></i> Админ
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                        <i class="bi bi-box-seam"></i> Заказы
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.no-category') }}">
                                        <i class="bi bi-exclamation-triangle"></i> Товары без категорий
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.with-category') }}">
                                        <i class="bi bi-tags"></i> Товары с категориями
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('catalog') }}">
                                        <i class="bi bi-grid"></i> Каталог товаров
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.reviews.index') }}">
                                        <i class="bi bi-chat-dots"></i> Отзывы
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.sync') }}">
                                        <i class="bi bi-arrow-repeat"></i> Синхронизация с МойСклад
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? 'Профиль' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">Личный кабинет</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.history') }}">Мои заказы</a></li>

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Вход</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
                @endauth


            </ul>
        </div>
    </div>
</nav>

@push('styles')
    <style>
        /* ===== АНИМАЦИЯ ССЫЛОК В ШАПКЕ ===== */
        .navbar-nav .nav-link {
            position: relative;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: #0EA5E9;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 80%;
            left: 10%;
        }

        .navbar-nav .nav-link:hover {
            color: #0EA5E9 !important;
        }

        /* ===== ВЫПАДАЮЩИЕ ПОДКАТЕГОРИИ ===== */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -5px;
            display: none;
            border-radius: 8px;
            min-width: 200px;
        }

        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }

        .dropdown-submenu > .dropdown-toggle::after {
            content: "›";
            float: right;
            border: none;
            font-size: 1.2rem;
            margin-top: -2px;
        }

        /* ===== ГОРИЗОНТАЛЬНОЕ ВЫРАВНИВАНИЕ ===== */
        .navbar .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .navbar-collapse {
            flex-grow: 1;
        }

        .navbar-nav.mx-auto {
            flex-wrap: wrap;
            justify-content: center;
        }

        .search-form {
            width: 250px;
            flex-shrink: 0;
        }

        .search-form .position-relative {
            width: 100%;
        }

        .search-form .form-control {
            width: 100%;
            padding-right: 40px;
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            padding: 5px 10px;
        }

        /* Корзина */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Адаптив */
        @media (max-width: 1200px) {
            .search-form {
                width: 200px;
            }
            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 992px) {
            .navbar-collapse {
                margin-top: 1rem;
            }
            .search-form {
                width: 100%;
                margin: 1rem 0;
            }
            .dropdown-submenu > .dropdown-menu {
                left: 0;
                margin-left: 20px;
            }
            .dropdown-submenu:hover > .dropdown-menu {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        if (window.innerWidth <= 992) {
            document.querySelectorAll('.dropdown-submenu > .dropdown-toggle').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let submenu = this.nextElementSibling;
                    if (submenu) {
                        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    }
                });
            });
        }
    </script>
@endpush
