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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ asset('js/booking.js') }}" defer></script>
    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script>
        // Добавляем ID экскурсии для JavaScript
        const excursionId = {{ $excursion->id }};
        
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

        <h1 class="excursion-title">{{ $excursion->title }}</h1>

            <!-- Основная информация -->
            <div class="excursion-info">
                <div class="excursion-section">
                    <h2><i class="fas fa-hiking"></i> Описание экскурсии</h2>
                    <p>{{ $excursion->description ?? 'Не указан' }}</p>
            </div>

            <div class="excursion-image-gallery">
                <!-- Главное фото слева -->
                <div class="main-photo">
                    @if($excursion->previewPhoto())
                        <img src="{{ asset('storage/' . $excursion->previewPhoto()->photo_path) }}" alt="{{ $excursion->title }}" class="excursion-detail-image">
                    @else
                        <img src="{{ asset('img/placeholder.jpg') }}" alt="{{ $excursion->title }}" class="excursion-detail-image">
                    @endif
                </div>

                <!-- Дополнительные фото справа -->
                @if($excursion->photos->count() > 1)
                    <div class="side-photos">
                        @php
                            $additionalPhotos = $excursion->photos()->where('is_preview', false)->take(2)->get();
                        @endphp
                        @foreach($additionalPhotos as $index => $photo)
                            <div class="side-photo">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $excursion->title }}" class="side-photo-image">
                                @if($index === 1 && $excursion->photos->count() > 3)
                                    <div class="photo-overlay" onclick="openGallery()">
                                        +{{ $excursion->photos->count() - 3 }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="excursion-section">
                    <h2><i class="fas fa-hiking"></i> Уровень подготовки</h2>
                    <p>@switch($excursion->preparation_level)
                        @case('easy')
                            Легкий
                            @break
                        @case('medium')
                            Средний
                            @break
                        @case('hard')
                            Сложный
                            @break
                        @default
                            Не указан
                    @endswitch</p>
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
                                        @if(($excursion->availableSeats('a', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? 0) <= 0) disabled @endif required>
                                    <label class="form-check-label" for="group_a">
                                        Группа A (Осталось мест: {{ $excursion->availableSeats('a', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? '0' }})
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="group_type" id="group_b" value="b"
                                        @if(($excursion->availableSeats('b', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? 0) <= 0) disabled @endif required>
                                    <label class="form-check-label" for="group_b">
                                        Группа B (Осталось мест: {{ $excursion->availableSeats('b', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? '0' }})
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="group_type" id="group_c" value="c"
                                        @if(($excursion->availableSeats('c', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? 0) <= 0) disabled @endif required>
                                    <label class="form-check-label" for="group_c">
                                        Группа C (Осталось мест: {{ $excursion->availableSeats('c', request('excursion_date', $excursion->start_date->format('Y-m-d'))) ?? '0' }})
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

        <!-- Галерея в модальном окне -->
        <div class="gallery-modal" id="galleryModal">
            <div class="gallery-content">
                <span class="close-gallery" onclick="closeGallery()">×</span>
                <div class="gallery-images">
                    @foreach($excursion->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $excursion->title }}" class="gallery-image" style="cursor:pointer;">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Модальное окно для увеличенного фото -->
        <div id="imageModal" class="modal" style="display:none;">
            <span class="close" id="closeModal">&times;</span>
            <img class="modal-content" id="modalImg">
        </div>

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
        </section>
    </main>

    
    <section class="route-builder">
    <h2>Как добраться до начала экскурсии</h2>
    <div class="route-input">
        <label for="origin">Откуда вы поедете:</label>
        <input type="text" id="origin" placeholder="Введите ваш адрес (например, Екатеринбург, ул. Ленина)" value="">
        <input type="hidden" id="destination" value="{{ $excursion->latitude && $excursion->longitude ? $excursion->latitude . ',' . $excursion->longitude : $excursion->title }}">
        <button class="card" onclick="buildRoute()">Построить маршрут</button>
    </div>
    <div id="map" style="height: 800px; width: 100%; margin-top: 20px;"></div>
    <div id="route-info">
        <p><strong>Расстояние:</strong> <span id="distance"></span></p>
        <p><strong>Время в пути:</strong> <span id="duration"></span></p>
    </div>
</section>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ config('yandex.api_key') }}" type="text/javascript"></script>
<script>
    console.log("API Key loaded: {{ config('yandex.api_key') }}");

    if (typeof ymaps === 'undefined') {
        console.error("Яндекс.Карты API не загружено. Проверьте API-ключ и подключение.");
    } else {
        ymaps.ready(function () {
            let map, route;

            // Устанавливаем начальный центр карты
            map = new ymaps.Map("map", {
                center: [56.8389, 60.6057], // Координаты Екатеринбурга (Урал)
                zoom: 7,
            });

            window.buildRoute = function () {
                const origin = document.getElementById("origin").value;
                const destination = document.getElementById("destination").value;

                console.log("Origin:", origin);
                console.log("Destination:", destination);

                if (!origin || !destination) {
                    alert("Пожалуйста, укажите точку отправления.");
                    return;
                }

                // Очищаем предыдущий маршрут
                if (route) map.geoObjects.remove(route);

                // Строим маршрут
                ymaps.route([origin, destination], {
                    mapStateAutoApply: true,
                    routingMode: 'auto', // Автомобильный маршрут
                }).then(function (newRoute) {
                    route = newRoute;
                    map.geoObjects.add(route);

                    // Кастомизация стиля маршрута
                    route.getPaths().options.set({
                        strokeColor: '#FF0000', // Красная линия
                        strokeWidth: 4,
                        opacity: 0.7
                    });

                    // Получаем данные
                    let distance = route.getHumanLength();
                    let duration = route.getHumanTime();

                    // Отладка: выводим исходные данные и коды символов
                    console.log("Raw Distance:", distance);
                    console.log("Raw Distance char codes:", distance.split('').map(char => char.charCodeAt(0)));
                    console.log("Raw Duration:", duration);
                    console.log("Raw Duration char codes:", duration.split('').map(char => char.charCodeAt(0)));

                    // Очищаем от HTML-кодов и неразрывных пробелов
                    distance = distance.replace(/(&\#160;|\u00A0|\u2000-\u200B|\u202F|\u205F|\u3000)/g, ' ').trim();
                    duration = duration.replace(/(&\#160;|\u00A0|\u2000-\u200B|\u202F|\u205F|\u3000)/g, ' ').trim();

                    // Удаляем пробелы внутри чисел (например, "1 708" → "1708")
                    distance = distance.replace(/(\d+)\s+(\d+)/g, '$1$2');
                    duration = duration.replace(/(\d+)\s+(\d+)/g, '$1$2');

                    // Отладка: смотрим очищенные строки
                    console.log("Cleaned Distance:", distance);
                    console.log("Cleaned Duration:", duration);

                    // Ручное форматирование для расстояния
                    const distanceMatch = distance.match(/^(\d+)\s*км$/i);
                    if (distanceMatch) {
                        distance = `${distanceMatch[1]} км`;
                    } else {
                        distance = 'Не удалось определить расстояние';
                        console.warn("Не удалось распознать расстояние:", distance);
                    }

                    // Ручное форматирование для времени
                    let durationMatch;

                    // Пробуем разные форматы для времени
                    // Формат: "11 ч 16 мин" или "11ч 16мин"
                    durationMatch = duration.match(/(\d+)\s*ч\s*(\d+)\s*мин/i) || duration.match(/(\d+)ч\s*(\d+)мин/i);
                    if (durationMatch) {
                        duration = `${durationMatch[1]} ч ${durationMatch[2]} мин`;
                    } else {
                        // Формат: "11 ч" или "11ч"
                        durationMatch = duration.match(/(\d+)\s*ч/i) || duration.match(/(\d+)ч/i);
                        if (durationMatch) {
                            duration = `${durationMatch[1]} ч`;
                        } else {
                            // Формат: "1234 мин" или "1234мин"
                            durationMatch = duration.match(/(\d+)\s*мин/i) || duration.match(/(\d+)мин/i);
                            if (durationMatch) {
                                const totalMinutes = parseInt(durationMatch[1], 10);
                                const hours = Math.floor(totalMinutes / 60);
                                const minutes = totalMinutes % 60;
                                duration = hours > 0 ? `${hours} ч ${minutes} мин` : `${minutes} мин`;
                            } else {
                                // Резервный способ: извлекаем числа и единицы измерения вручную
                                const parts = duration.split(/\s+/);
                                console.log("Duration parts:", parts);
                                let hours = 0, minutes = 0;
                                for (let i = 0; i < parts.length; i++) {
                                    if (parts[i].match(/\d+/)) {
                                        if (parts[i+1] && parts[i+1].includes('ч')) {
                                            hours = parseInt(parts[i], 10);
                                        } else if (parts[i+1] && parts[i+1].includes('мин')) {
                                            minutes = parseInt(parts[i], 10);
                                        }
                                    }
                                }
                                if (hours > 0 || minutes > 0) {
                                    duration = hours > 0 ? `${hours} ч` : '';
                                    duration += (hours > 0 && minutes > 0) ? ' ' : '';
                                    duration += minutes > 0 ? `${minutes} мин` : '';
                                } else {
                                    duration = 'Не удалось определить время';
                                    console.warn("Не удалось распознать время:", duration);
                                }
                            }
                        }
                    }

                    // Отображаем расстояние и время
                    document.getElementById("distance").textContent = distance;
                    document.getElementById("duration").textContent = duration;

                    // Центрируем карту по маршруту
                    const paths = route.getPaths();
                    if (paths.getLength() > 0) {
                        const bounds = paths.getBounds();
                        if (bounds) {
                            map.setBounds(bounds, { checkZoomRange: true });
                        } else {
                            console.warn("Границы маршрута не определены.");
                        }
                    } else {
                        console.warn("Маршрут не содержит путей.");
                    }
                }).catch(function (error) {
                    console.error("Ошибка маршрута:", error);
                    if (error.message === "can't construct a route") {
                        console.error("Детали ошибки: Невозможно построить маршрут. Проверьте точки origin и destination.");
                        alert("Не удалось построить маршрут. Убедитесь, что адреса корректны или уточните точку назначения.");
                    } else {
                        alert("Не удалось построить маршрут: " + error.message);
                    }
                });
            };
        });
    }
</script>

<style>
    .route-builder {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        margin: 20px 0;
    }
    .route-input {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }
    .route-input input[type="text"] {
        padding: 10px;
        width: 100%;
        max-width: 400px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .route-input button {
        padding: 10px 20px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-family: 'InterLight';
    }
    .route-input button:hover {
        background: #45a049;
    }
    #route-info {
        margin-top: 10px;
        font-size: 16px;
    }
    #route-info span {
        white-space: normal; /* Отключаем pre-wrap, так как мы вручную форматируем */
    }
</style>

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

    <!-- JavaScript для галереи -->
    <script>
        function openGallery() {
            document.getElementById('galleryModal').style.display = 'block';
        }

        function closeGallery() {
            document.getElementById('galleryModal').style.display = 'none';
        }

        // Увеличение фото при клике
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.gallery-image').forEach(function(img) {
                img.onclick = function() {
                    document.getElementById('imageModal').style.display = "block";
                    document.getElementById('modalImg').src = this.src;
                }
            });
            document.getElementById('closeModal').onclick = function() {
                document.getElementById('imageModal').style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == document.getElementById('imageModal')) {
                    document.getElementById('imageModal').style.display = "none";
                }
            }
        });
    </script>
</body>
</html>