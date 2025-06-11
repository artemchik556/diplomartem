<!DOCTYPE html>
<html lang="ru">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Экскурсии по Уралу</title>
    <link rel="stylesheet" href="{{ asset('css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modul.css') }}">
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
                <div id="consultationModal" class="modal">
                    <div class="modal-content">
                        <span class="close">×</span>
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
            <h2>ПОКОРИ ВЕРШИНЫ ГОР</h2>
            <h1>ЭКСКУРСИИ ПО УРАЛУ</h1>
        </div>
    </section>

    <!-- Поп-ап окно авторизации -->
    <div id="auth-popup" class="popup">
        <div class="popup-content">
            <span class="close" id="close-popup">×</span>
            <div class="tabs">
                <button class="tab-link active" id="tab-register">Регистрация</button>
                <button class="tab-link" id="tab-login">Войти</button>
            </div>
            <div id="register-form" class="form active">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Имя" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="text" name="phone" placeholder="Телефон" required>
                    <input type="password" name="password" placeholder="Введите пароль" required>
                    <button type="submit">Регистрация</button>
                </form>
            </div>
            <div id="login-form" class="form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="password" placeholder="Введите пароль" required>
                    <button type="submit">Войти</button>
                </form>
            </div>
        </div>
    </div>

    @php
    // Удаляем этот блок, так как теперь используем $popularExcursions из контроллера
    // $excursions = App\Models\Excursion::latest()->take(4)->get();
    @endphp

    <h2 class="header2">Популярные экскурсии</h2>
    <section class="cads-block">
        @foreach ($popularExcursions as $excursion)
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
    </section>
    <div class="content">
        <div class="container">
            <div class="application">
                <div class="application-block">
                    <div class="application-block-wraper">
                        <p>Если у Вас есть вопросы или сомнения - оставьте заявку</p>
                        <p>НА БЕСПЛАТНУЮ КОНСУЛЬТАЦИЮ С НАШИМ ГИДОМ!</p>
                    </div>
                    <div class="application-click-2">
                        <button id="openModalMain" class="clicks">Оставить звонок</button>
                    </div>
                </div>
                <div id="consultationModal" class="modal">
                    <div class="modal-content">
                        <h2>Заказать консультацию</h2>
                        <form id="consultationForm" method="POST" action="{{ route('consultations.store') }}">
                            @csrf
                            <input type="text" id="name" name="name" placeholder="Ваше имя" required>
                            <input type="email" id="email" name="email" placeholder="Ваш email" required>
                            <input type="tel" id="phone" name="phone" placeholder="Ваш телефон" required>
                            <button type="submit">Отправить заявку</button>
                        </form>
                        <div class="features">
                            <h3>Что вы получите:</h3>
                            <ul>
                                <li>Бесплатную консультацию по выбору экскурсии</li>
                                <li>Индивидуальный подбор маршрута</li>
                                <li>Ответы на все ваши вопросы</li>
                                <li>Специальные предложения и скидки</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="application-block2">
                    <div class="application-block2-wraper">
                        <p>Формат: 15-30 минут общения, вопрос-ответ, погружение в атмосферу выбранного маршрута</p>
                    </div>
                    <div class="application-block2-img">
                        <img class="application-img" src="img/gora.svg" alt="Гора">
                    </div>
                </div>
            </div>
        </div>

        <h2 class="header2">Почему выбирают нас?</h2>
        <div class="feature-fon">
            <div class="feature-item">
                <div class="feature">
                    <h2>ПОМОЩЬ В ПОДГОТОВКЕ</h2>
                    <p>Мы обеспечиваем участников надёжным снаряжением, помогаем в выборе одежды, организуем прокат инвентаря и договариваемся о скидках для группы.</p>
                </div>
                <div class="feature">
                    <h2>БЕЗОПАСНОСТЬ</h2>
                    <p>На каждого гида не более трёх участников. Перед походами все участники получают индивидуальную программу тренировок и вступают в чат поддержки с гидами. На горе перед восхождением проводим ледовые занятия.</p>
                </div>

                <div class="feature">
                    <h2>НАДЕЖНАЯ КОМАНДА</h2>
                    <p>За плечами каждого гида тысячи горных километров, годы опыта, сотни часов обучения и подготовки. Наши гиды имеют спортивные разряды по альпинизму, ежегодно повышают квалификацию и проходят курсы оказания первой помощи.</p>
                </div>

                <div class="feature">
                    <h2>ЗАБОТА О ЛЮДЯХ</h2>
                    <p>Мы внимательно относимся к запросам участников, помогаем на каждом этапе похода, доводим группу до намеченной цели. Поддерживаем в любых ситуациях</p>
                </div>
            </div>
            <div class="feature-img">
                <img src="img/mountion.png" alt="">
            </div>
        </div>

        <div class="fag">
            <div class="faq-container">
                <h2 class="header3">Ответы на частые вопросы</h2>
                <div class="faq-item">
                    <div class="faq-question">
                        Где находится офис вашей компании?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">Офис находится в городе Набережные Челны, мы работаем дистанционно так же ждем вас у нас, а представители каждого региона живут в городах своего направления.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Можно ли поехать с детьми?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">В наши туры можно поехать с детьми от 7-ми лет. Если ваш ребенок активный, любит приключения и был в подобных турах, то можем взять 5-ти и 6-ти летних детей.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Входит ли в стоимость тура перелет?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">В стоимость тура уже включено: питание, проживание, транспорт, экскурсии. Перелет оплачиваете отдельно, а мы с радостью подберем для вас самые комфортные варианты.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Сколько предоплата?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">У нас нету пред оплаты, оплата экскурсии у нас в офисе или через гида.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Я еду один, часто ездят одни?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">В наши туры довольно часто туристы приезжают по одному и заводят новые знакомства и друзей.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Как осуществляется бронирование?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">Для бронирования тура нужно будет внести свои данные и данные о экскурсии на сайте в разделе экскурсии и выбрав экскурсию.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Как с нами связаться?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">Связаться с нами можно по номеру телефона, электронной почте а так же посетить нас лично по адресу: Набережные Челны 56/04A</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Насколько безопасны ваши туры?
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">Безопасность — наш главный приоритет. Мы тщательно разрабатываем маршруты, ближайшие погодные условия, сложность местности и уровень подготовки участника.</div>
                </div>
            </div>
        </div>
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
    <script src="{{ asset('js/question.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/form-validation.js') }}"></script>
    <script>
        // Get modal elements
        const modal = document.getElementById('consultationModal');
        const closeBtn = document.getElementsByClassName('close')[0];
        const form = document.getElementById('consultationForm');

        // Open modal when clicking the consultation button
        document.getElementById('openModal').onclick = function() {
            modal.style.display = "block";
        }

        // Close modal when clicking the close button
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Handle form submission
        form.onsubmit = function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(form);

            // Send form data to server
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
                        modal.style.display = "none";
                        form.reset();
                    } else {
                        alert('Произошла ошибка. Пожалуйста, попробуйте позже.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка. Пожалуйста, попробуйте позже.');
                });
        }
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
</body>
@include('auth.login')
@include('auth.register')

<style>
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 5px;
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Form styles */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #F8B13C;
    }

    .submit-btn {
        background-color: #F8B13C;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    .submit-btn:hover {
        background-color: #e69c2e;
    }

    /* Features list styles */
    .features-list {
        margin-top: 20px;
        padding: 0;
        list-style: none;
    }

    .features-list li {
        margin-bottom: 10px;
        padding-left: 25px;
        position: relative;
    }

    .features-list li:before {
        content: "✓";
        color: #F8B13C;
        position: absolute;
        left: 0;
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

</html>