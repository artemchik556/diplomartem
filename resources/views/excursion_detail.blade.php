<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $excursion->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/excurs.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modul.css') }}">
    <link rel="stylesheet" href="{{ asset('css/excursion-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ asset('js/booking.js') }}" defer></script>
    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Popup script loaded'); // Отладка: проверяем загрузку скрипта
            document.addEventListener('click', function(e) {
                console.log('Click event triggered on:', e.target); // Отладка: какой элемент кликнут
                if (e.target.classList.contains('btn-close-popup') || e.target.classList.contains('discount-popup-overlay')) {
                    console.log('Attempting to close popup'); // Отладка: попытка закрытия
                    const overlay = document.querySelector('.discount-popup-overlay');
                    if (overlay) {
                        overlay.classList.add('hidden');
                        console.log('Popup hidden'); // Отладка: попап скрыт
                    } else {
                        console.log('Overlay not found'); // Отладка: элемент не найден
                    }
                }
            });
        });
    </script>
</head>

<body>
    <!-- Шапка сайта -->
    <header class="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="Твой Тур">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="{{ url('/') }}">Главная</a></li>
                <li><a href="{{ route('excursions') }}">Экскурсии</a></li>
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

    <!-- Основной контент -->
    <main class="excursion-wrapper">
        <!-- Баннер экскурсии -->
        <section class="excursion-banner">
            <div class="excursion-image-container">
                <img src="{{ $excursion->detail_image ? asset('storage/' . $excursion->detail_image) : asset('img/placeholder.jpg') }}"
                    alt="{{ $excursion->title }}" class="excursion-detail-image">
            </div>
        </section>

        <!-- Информация об экскурсии -->
        <section class="excursion-content">
            <h1 class="excursion-title">{{ $excursion->title }}</h1>

            <!-- Основная информация -->
            <div class="excursion-info">
                <div class="excursion-section">
                    <h2><i class="fas fa-hiking"></i> Описание экскурсии</h2>
                    <p>{{ $excursion->description ?? 'Не указан' }}</p>
                </div>

                <div class="excursion-section">
                    <h2><i class="fas fa-hiking"></i> Уровень подготовки</h2>
                    <p>{{ $excursion->preparation_level ?? 'Не указан' }}</p>
                </div>

                <div class="excursion-section">
                    <h2><i class="fas fa-hiking"></i> Стоимость однодневного тура</h2>
                    <p>{{ $excursion->price ?? 'Не указан' }}</p>
                </div>

                <div class="excursion-section">
                    <h2><i class="fas fa-route"></i> Как добраться</h2>
                    <div class="transport-methods">
                        <div class="transport-method">
                            <h3><i class="fas fa-car"></i> На машине</h3>
                            <p>{{ $excursion->transport_car ?? 'Информация отсутствует' }}</p>
                        </div>
                        <div class="transport-method">
                            <h3><i class="fas fa-bus"></i> На автобусе</h3>
                            <p>{{ $excursion->transport_bus ?? 'Информация отсутствует' }}</p>
                        </div>
                        <div class="transport-method">
                            <h3><i class="fas fa-train"></i> На поезде</h3>
                            <p>{{ $excursion->transport_train ?? 'Информация отсутствует' }}</p>
                        </div>
                    </div>
                </div>

                <div class="guide-info">
                    <h3><i class="fas fa-user-tie"></i> Гид: {{ $excursion->guide ? $excursion->guide->name : 'Не указан' }}</h3>
                    <p>{{ $excursion->guide ? $excursion->guide->position : 'Не указана должность' }}</p>
                </div>
            </div>

            <!-- Блок бронирования -->
            @auth
            <div class="booking-section">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="booking-form">
                    <h2><i class="fas fa-ticket-alt"></i> Забронировать</h2>
                    <form action="{{ route('excursions.book', $excursion) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Выберите группу:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_type" id="group_a" value="a"
                                    @if(($excursion->group_a_seats ?? 0) <= 0) disabled @endif required>
                                <label class="form-check-label" for="group_a">
                                    Группа A (Осталось мест: {{ $excursion->group_a_seats ?? '0' }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_type" id="group_b" value="b"
                                    @if(($excursion->group_b_seats ?? 0) <= 0) disabled @endif required>
                                <label class="form-check-label" for="group_b">
                                    Группа B (Осталось мест: {{ $excursion->group_b_seats ?? '0' }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_type" id="group_c" value="c"
                                    @if(($excursion->group_a_seats ?? 0) <= 0) disabled @endif required>
                                <label class="form-check-label" for="group_c">
                                    Группа C (Осталось мест: {{ $excursion->group_c_seats ?? '0' }})
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="excursion_date">Дата экскурсии:</label>
                            <input type="date" name="excursion_date" id="excursion_date" class="form-control" required
                                min="{{ $excursion->start_date->format('Y-m-d') }}"
                                max="{{ $excursion->end_date->format('Y-m-d') }}">
                            <div class="date-range-info">
                                Доступные даты: {{ $excursion->start_date->format('d.m.Y') }} - {{ $excursion->end_date->format('d.m.Y') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="number_of_people">Количество человек:</label>
                            <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" max="10" required>
                        </div>

                        <button type="submit" class="btn-primary">Забронировать</button>
                    </form>
                </div>
            </div>

            <!-- Форма отзыва -->
            <div class="review-form">
                <h2><i class="fas fa-star"></i> Оставить отзыв</h2>
                <form action="{{ route('reviews.store', $excursion) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Оценка:</label>
                        <div class="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" required />
                            <label for="star{{$i}}" title="{{$i}} звезд{{$i == 1 ? 'а' : ($i < 5 ? 'ы' : '')}}">
                                <i class="fas fa-star"></i>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Ваш отзыв:</label>
                        <textarea name="comment" id="comment" required minlength="10" placeholder="Напишите ваш отзыв здесь..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Отправить отзыв</button>
                </form>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Для бронирования необходимо <a href="javascript:void(0);" onclick="openPopup('login-popup')">войти</a> в систему
            </div>
            @endauth

            <!-- Секция отзывов -->
            <div class="reviews-section">
                <h2><i class="fas fa-comments"></i> Отзывы</h2>
                @if($excursion->approvedReviews->isEmpty())
                <p class="no-reviews">Пока нет отзывов</p>
                @else
                <div class="reviews-container">
                    @foreach($excursion->approvedReviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'active' : 'inactive' }}"></i>
                                @endfor
                                <span class="rating-value">({{ $review->rating }} из 5)</span>
                            </div>
                            <div class="review-author">
                                <i class="fas fa-user"></i> {{ $review->user->name }}
                            </div>
                            <div class="review-date">
                                <i class="far fa-calendar-alt"></i> {{ $review->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                        <div class="review-content">
                            {{ $review->comment }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </section>

            @if(session('show_discount'))
            <div class="discount-popup-overlay">
                <div class="discount-popup">
                    <div class="discount-content">
                        <h3>Поздравляем!</h3>
                        <p>Вы получили 10% скидку за бронирование на 5 и более человек!</p>
                        <div class="price-details">
                            <div class="price-row">
                                <span>Стоимость без скидки:</span>
                                <span>{{ number_format(session('original_price'), 0, ',', ' ') }} ₽</span>
                            </div>
                            <div class="price-row discount">
                                <span>Скидка 10%:</span>
                                <span>-{{ number_format(session('discount_amount'), 0, ',', ' ') }} ₽</span>
                            </div>
                            <div class="price-row total">
                                <span>Итоговая стоимость:</span>
                                <span>{{ number_format(session('final_price'), 0, ',', ' ') }} ₽</span>
                            </div>
                        </div>
                        <button class="btn-close-popup">Закрыть</button>
                    </div>
                </div>
            </div>
            @endif
    </main>

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


    
    <!-- Модальные окна для входа и регистрации -->
    @include('auth.login')
    @include('auth.register')
</body>

</html>