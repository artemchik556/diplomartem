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
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<button id="scrollTopBtn" class="scroll-top-btn" title="Наверх">
    <i class="fas fa-arrow-up"></i>
</button>
<script>
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    });
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>


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
                <div class="application-click-1">
                    <button id="openModalHeader" class="call-button">Оставить звонок</button>
                </div>

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
        <aside class="filters">
    <h3>Фильтры</h3>
    <form id="filters-form" method="GET">
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
            <button type="button" name="sort" value="price_asc" {{ request('sort') == 'price_asc' ? 'class=active' : '' }}>По возрастанию</button>
            <button type="button" name="sort" value="price_desc" {{ request('sort') == 'price_desc' ? 'class=active' : '' }}>По убыванию</button>
        </div>

        <label class="title" for="rating">Рейтинг:</label>
        <div class="sort-buttons">   
            <button type="button" name="sort" value="rating_asc" {{ request('sort') == 'rating_asc' ? 'class=active' : '' }}>По возрастанию</button>
            <button type="button" name="sort" value="rating_desc" {{ request('sort') == 'rating_desc' ? 'class=active' : '' }}>По убыванию</button>
        </div>

        <!-- Фильтрация по направлению -->
        <div class="filter-group">
            <label class="title" for="location">Направление:</label>
            <select class="locations" name="location">
                <option value="">Все направления</option>
                <option value="Челябинская область" {{ request('location') == 'Челябинская область' ? 'selected' : '' }}>Челябинская область</option>
                <option value="Ханты-Мансийский АО" {{ request('location') == 'Ханты-Мансийский АО' ? 'selected' : '' }}>Ханты-Мансийский АО</option>
                <option value="Респ. Башкортастан" {{ request('location') == 'Респ. Башкортастан' ? 'selected' : '' }}>Респ. Башкортастан</option>
                <option value="Свердловская область" {{ request('location') == 'Свердловская область' ? 'selected' : '' }}>Свердловская область</option>
                <option value="Пермьская область" {{ request('location') == 'Пермьская область' ? 'selected' : '' }}>Пермьская область</option>
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

        <!-- Скрытое поле для сортировки -->
        <input type="hidden" name="sort" value="{{ request('sort') }}">

        <div class="filter-buttons">
            <button type="submit" class="btn btn-reset">Применить фильтры</button>
            <a href="{{ url('excurcion') }}" class="btn btn-reset">Сбросить фильтры</a>
        </div>
    </form>
</aside>

        <!-- Список экскурсий (правая колонка) -->
        <main class="cads-block" id="excursions-list">
            @foreach ($excursions as $excursion)
            <a href="{{ route('excursion.detail', $excursion->id) }}" class="cards-link">
                <div class="cards">
                    <div class="excursion-card">
                        <div class="rating-badge">
                            <span class="rating-number">{{ number_format($excursion->average_rating, 1) }}</span>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($excursion->average_rating) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <img class="size" src="{{ $excursion->previewPhoto() ? asset('storage/' . $excursion->previewPhoto()->photo_path) : asset('img/placeholder.jpg') }}" alt="{{ $excursion->title }}">
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

            .rating-badge {
                position: absolute;
                top: 10px;
                left: 10px;
                background: rgba(255, 255, 255, 0.9);
                padding: 5px 10px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 5px;
                z-index: 1;
            }

            .rating-number {
                font-weight: bold;
                font-size: 1.1em;
            }

            .stars {
                display: flex;
                gap: 2px;
            }

            .stars i {
                color: #ddd;
            }

            .stars i.active {
                color: #ffd700;
            }

            .excursion-card {
                position: relative;
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
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/form-validation.js') }}"></script>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("consultationModal");
            const openModalBtns = document.querySelectorAll(".call-button");
            const closeModalBtn = document.querySelector(".close");
            const form = document.getElementById("consultationForm");

            // Открытие модального окна при клике на любую из кнопок
            openModalBtns.forEach(btn => {
                btn.addEventListener("click", () => modal.style.display = "block");
            });

            // Закрытие модального окна
            if (closeModalBtn) {
                closeModalBtn.addEventListener("click", () => modal.style.display = "none");
            }
            window.addEventListener("click", (e) => {
                if (e.target === modal) modal.style.display = "none";
            });

            // Обработка отправки формы
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || "Спасибо! Мы свяжемся с вами в ближайшее время.");
                        modal.style.display = "none";
                        form.reset();
                    })
                    .catch(error => {
                        console.error("Ошибка:", error);
                        alert("Произошла ошибка при отправке формы. Пожалуйста, попробуйте позже.");
                    });
                });
            }
        });
    </script> 
                
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtersForm = document.getElementById('filters-form');
    const excursionsList = document.getElementById('excursions-list');
    let loadMoreBtn = document.getElementById('load-more');

    // Обработчик для кнопок сортировки
    document.querySelectorAll('.sort-buttons button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const sortInput = filtersForm.querySelector('input[name="sort"]');
            sortInput.value = this.value;
            filtersForm.dispatchEvent(new Event('submit'));
        });
    });

    // Обработчик отправки формы
    filtersForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(filtersForm);
        const url = new URL('{{ url("excurcion") }}');
        
        formData.forEach((value, key) => {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        url.searchParams.set('page', '1');

        excursionsList.innerHTML = '<div class="loading">Загрузка...</div>';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            excursionsList.innerHTML = data.html || '<div class="no-results">Экскурсии не найдены</div>';

            if (data.hasMore) {
                if (!loadMoreBtn) {
                    const newLoadMoreBtn = document.createElement('button');
                    newLoadMoreBtn.id = 'load-more';
                    newLoadMoreBtn.className = 'btn-load-more';
                    newLoadMoreBtn.dataset.page = '2';
                    newLoadMoreBtn.textContent = 'Показать больше';
                    excursionsList.insertAdjacentElement('afterend', newLoadMoreBtn);
                    loadMoreBtn = newLoadMoreBtn;
                    addLoadMoreListener(loadMoreBtn);
                } else {
                    loadMoreBtn.dataset.page = '2';
                    loadMoreBtn.style.display = 'block';
                }
            } else if (loadMoreBtn) {
                loadMoreBtn.remove();
                loadMoreBtn = null;
            }

            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);
            excursionsList.innerHTML = '<div class="error">Ошибка при загрузке экскурсий</div>';
        });
    });

    // Обработчик сброса фильтров
    document.querySelector('.btn-reset[href]').addEventListener('click', function(e) {
        e.preventDefault();
        filtersForm.reset();
        const url = new URL('{{ url("excurcion") }}');
        url.searchParams.set('page', '1');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            excursionsList.innerHTML = data.html || '<div class="no-results">Экскурсии не найдены</div>';
            if (data.hasMore) {
                if (!loadMoreBtn) {
                    const newLoadMoreBtn = document.createElement('button');
                    newLoadMoreBtn.id = 'load-more';
                    newLoadMoreBtn.className = 'btn-load-more';
                    newLoadMoreBtn.dataset.page = '2';
                    newLoadMoreBtn.textContent = 'Показать больше';
                    excursionsList.insertAdjacentElement('afterend', newLoadMoreBtn);
                    loadMoreBtn = newLoadMoreBtn;
                    addLoadMoreListener(loadMoreBtn);
                } else {
                    loadMoreBtn.dataset.page = '2';
                    loadMoreBtn.style.display = 'block';
                }
            } else if (loadMoreBtn) {
                loadMoreBtn.remove();
                loadMoreBtn = null;
            }

            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);
            excursionsList.innerHTML = '<div class="error">Ошибка при загрузке экскурсий</div>';
        });
    });

    // Функция для добавления обработчика кнопки "Показать больше"
    function addLoadMoreListener(button) {
        button.addEventListener('click', function() {
            const page = button.getAttribute('data-page');
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);

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
                excursionsList.insertAdjacentHTML('beforeend', data.html);
                const newPage = parseInt(page) + 1;
                button.setAttribute('data-page', newPage);

                if (!data.hasMore) {
                    button.remove();
                    loadMoreBtn = null;
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

    // Добавляем обработчик для существующей кнопки "Показать больше"
    if (loadMoreBtn) {
        addLoadMoreListener(loadMoreBtn);
    }
});
</script>
</body>
@include('auth.login')
@include('auth.register')

<div id="consultationModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Заказать консультацию</h2>
                <form id="consultationForm" method="POST" action="{{ route('consultations.store') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Ваш email" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" placeholder="Ваш телефон" required>
                    </div>
                    <button type="submit" class="submit-button">Отправить заявку</button>
                </form>
                <div class="features">
                    <h3>Что вы получите:</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Бесплатную консультацию по выбору экскурсии</li>
                        <li><i class="fas fa-check"></i> Индивидуальный подбор маршрута</li>
                        <li><i class="fas fa-check"></i> Ответы на все ваши вопросы</li>
                        <li><i class="fas fa-check"></i> Специальные предложения и скидки</li>
                    </ul>
                </div>
            </div>
        </div>

</html>