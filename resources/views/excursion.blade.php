<!DOCTYPE html>
<html lang="ru">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Экскурсии по Уралу</title>
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modul.css') }}">
    <link rel="stylesheet" href="{{ asset('css/excurcion.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="Твой Тур">
            </a>
        </div>

        <div class="burger-menu">
            <div class="burger-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <nav class="nav">
            <ul>
                <li><a href="{{ url('/') }}">Главная</a></li>
                <li><a href="{{ url('excurcion') }}">Экскурсии</a></li>
                <li><a href="{{ route('pereval') }}">История</a></li>
                <li><a href="{{ route('about') }}">О нас</a></li>

                @auth
                @if(Auth::user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}">Администрирование</a></li>
                @endif
                @endauth
            </ul>
        </nav>
        <div class="login">
            @guest
            <a href="javascript:void(0);" onclick="openPopup('login-popup')">Войти</a>
            @else
            <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="user">{{ Auth::user()->name }}</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endguest
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Выбери тур по душе — вершины ждут тебя!</h2>
        </div>
    </section>

    <!-- Основной контент -->
    <div class="content-wrapper">
        <!-- Фильтры (левая колонка) -->
        <!-- Фильтры (левая колонка) -->
            <aside class="filters">
                <h3>Фильтры</h3>
                <form action="{{ url('excurcion') }}" method="GET" id="filters-form">
                    <!-- Поиск по названию -->
                    <div class="filter-group">
                        <label class="title" for="search">Поиск:</label>
                        <input type="text" name="search" placeholder="Название экскурсии" value="{{ request('search') }}">
                    </div>

                    <!-- Фильтрация по цене -->
                    <div class="filter-group">
                        <label class="title" for="min-price">Цена:</label>
                        <input type="number" name="min_price" placeholder="от" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" placeholder="до" value="{{ request('max_price') }}">
                    </div>

                    <div class="sort-buttons">
                        <button type="submit" name="sort" value="asc">По возрастанию</button>
                        <button type="submit" name="sort" value="desc">По убыванию</button>
                    </div>

                    <!-- Фильтрация по направлению -->
                    <div class="filter-group">
                        <label class="title" for="location">Направление:</label>
                        <select class="locations" name="location">
                            <option value="">Все направления</option>
                            <option value="Челябинская область" {{ request('location') == 'Челябинская область' ? 'selected' : '' }}>Челябинская область</option>
                            <!-- остальные варианты -->
                        </select>
                    </div>

                    <!-- Фильтрация по сезону -->
                    <div class="filter-group">
                        <label class="title" for="season">Сезон:</label>
                        <select class="locations" name="season">
                            <option value="">Все сезоны</option>
                            <option value="winter" {{ request('season') == 'winter' ? 'selected' : '' }}>Зима</option>
                            <option value="spring" {{ request('season') == 'spring' ? 'selected' : '' }}>Весна</option>
                            <option value="summer" {{ request('season') == 'summer' ? 'selected' : '' }}>Лето</option>
                            <option value="autumn" {{ request('season') == 'autumn' ? 'selected' : '' }}>Осень</option>
                        </select>
                    </div>

                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-filter">Применить фильтры</button>
                        <a href="{{ url('excurcion') }}" class="btn btn-reset">Сбросить фильтры</a>
                    </div>
                </form>
            </aside>

        <!-- Список экскурсий (правая колонка) -->
        <!-- Список экскурсий -->
        <main class="cads-block" id="excursions-list">
            @foreach ($excursions as $excursion)
            <a href="{{ route('excursion.detail', $excursion->id) }}" class="cards-link">
                <div class="cards">
                    <div class="excursion-card">
                        <img class="size" src="{{ asset('storage/' . $excursion->image) }}" alt="{{ $excursion->title }}">
                        <div class="card-texts">
                            <div class="card-text">
                                <h3>{{ $excursion->title }}</h3>
                                <p class="price">{{ number_format($excursion->price, 0, ',', ' ') }} ₽</p>
                            </div>
                            <div class="card-text texts">
                                <p><i class="far fa-calendar-alt"></i> {{ $excursion->start_date->format('d.m.Y') }}</p>
                                <p><i class="fas fa-map-marker-alt"></i> {{ $excursion->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </main>

        @if($excursions->hasMorePages())
        <button id="load-more" class="btn-load-more" data-page="2">Показать больше</button>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('load-more');

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const button = this;
                        const page = button.getAttribute('data-page');

                        // Сохраняем все параметры URL
                        const url = new URL(window.location.href);
                        url.searchParams.set('page', page);

                        // Показываем состояние загрузки
                        button.textContent = 'Загрузка...';
                        button.disabled = true;

                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Добавляем новые экскурсии
                                document.getElementById('excursions-list').insertAdjacentHTML('beforeend', data.html);

                                // Обновляем номер страницы
                                const newPage = parseInt(page) + 1;
                                button.setAttribute('data-page', newPage);

                                // Если больше нет экскурсий - скрываем кнопку
                                if (!data.hasMore) {
                                    button.remove();
                                } else {
                                    button.textContent = 'Показать больше';
                                    button.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                button.textContent = 'Ошибка, попробуйте еще раз';
                                button.disabled = false;
                            });
                    });
                }
            });
        </script>

        <script>
            // Бургер меню
            const burgerMenu = document.querySelector('.burger-menu');
            const nav = document.querySelector('.nav');

            burgerMenu.addEventListener('click', () => {
                burgerMenu.classList.toggle('active');
                nav.classList.toggle('active');
            });

            // Закрытие меню при клике на ссылку
            const navLinks = document.querySelectorAll('.nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    burgerMenu.classList.remove('active');
                    nav.classList.remove('active');
                });
            });
        </script>

        <style>
            .btn-load-more {
                display: block;
                margin: 30px auto;
                padding: 12px 24px;
                background: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: all 0.3s;
            }

            .btn-load-more:hover {
                background: #45a049;
            }

            .btn-load-more:disabled {
                background: #cccccc;
                cursor: not-allowed;
            }
        </style>

    </div>

    <footer class="foote">
        <div class="logo-foote">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="Твой Тур">
            </a>
        </div>
        <div class="copy">
            <img src="{{ asset('img/copy.png') }}" alt="Копирайт">
            <p>Все права защищены</p>
        </div>
    </footer>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
@include('auth.login')
@include('auth.register')

</html>