<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>About</title>
    <link rel="stylesheet" href="{{ asset('css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-about.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

    <section class="heros">
        <div class="heros-text">
            <h1 class="Tur">Добро пожаловать в <span>ТвойТур</span><br> — компания,</h1>
            <div class="tur-text">
                <p>Наша миссия — сделать горный туризм доступным, безопасным и увлекательным. Мы разрабатываем тщательно продуманные маршруты для людей с разным уровнем подготовки.</p>
                <p>Мы заботимся о безопасности каждого участника путешествия. Все наши гиды — профессионалы с опытом работы в горных условиях, проходят предварительную проверку по сложности объекта и опасному риску. Мы используем современные технологии для отслеживания погодных условий и поддержания связи в режиме реального времени.</p>
                <p> Кроме того, мы уделяем пристальное внимание обучению туристов правилам поведения в горах и ориентации на местности. Вдохновляя уроки из прошлого, такие как история трагедий при перевале Дятлова, мы внедряем дополнительные меры предосторожности, чтобы минимизировать риски и проводить каждый экскурсию.</p>
            </div>
            <h2 class="header22">Окунуться в историю перевала Дятлова </h2>
            <div class="about-click">
                <button class="click1"> <a href="{{ route('pereval') }}">Перейти</a></button>
                <button class="click2"><a href="{{ route('excurcion') }}">Выбрать экскурсию</a></button>
            </div>
        </div>
    </section>

    <section class="swiper-layout">
        <h1 class="fotos-header">Погрузитесь в атмосферу горных приключений!</h1>
        <p class="fotos-text">Откройте для себя последнюю красоту Уральских горских пейзажей, которые вдохновляют и завораживают. Каждый снимок рассказывает свою историю о покорении вершин, бескрайних просторах и удивительной природе.</p>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="img/slider1.png" alt="slider1">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider2.png" alt="slider2">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider3.png" alt="slider3">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider4.png" alt="slider4">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider5.png" alt="slider5">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider6.png" alt="slider6">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider7.png" alt="slider7">
                </div>
                <div class="swiper-slide">
                    <img src="img/slider8.png" alt="slider8">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>


        <h2 class="header2">Наша команда</h2>

        <section class="team">
            <div class="teams">
                @forelse($guides as $guide)
                <div class="my-teams">
                    @if($guide->image)
                    <img src="{{ asset('storage/' . $guide->image) }}" alt="{{ $guide->name }}">
                    @else
                    <img src="{{ asset('img/default-guide.png') }}" alt="{{ $guide->name }}" class="default-image">
                    @endif
                    <p class="team-text">{{ $guide->position }}</p>
                    <p class="team-text2">{{ $guide->name }}</p>
                    <p class="team-text3">{{ $guide->description }}</p>
                    <p class="team-text4">Стаж: {{ $guide->experience }} лет</p>
                </div>
                @empty
                <p>Гидов пока нет.</p>
                @endforelse
            </div>
        </section>

        <h2 class="header2">Контакты</h2>

        <div class="contacts-container">
            <div class="contacts-info">
                <div class="contacts-text">
                    <p class="texts1">Адрес:</p>
                    <p class="texts2">Набережные Челны, 56/04А</p>
                </div>
                <div class="contacts-text">
                    <p class="texts1">Соцсети:</p>
                    <p class="texts2">@youtour</p>
                </div>
                <div class="contacts-text">
                    <p class="texts1">Номер телефна:</p>
                    <p class="texts2">+9 800-555-35-35</p>
                </div>
                <div class="contacts-text">
                    <p class="texts1">Почта</p>
                    <p class="texts2">youtour@mail.com</p>
                </div>
            </div>

            <div class="contacts-map">
                <iframe class="fram"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d243647.988749369!2d37.471166650000005!3d55.74979295000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNThcIEMwN0Qw!5e0!3m2!1sen!2sru!4v1614526859706!5m2!1sen!2sru"
                    width="809px"
                    height="591px"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>


        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 4,
                spaceBetween: 20,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    // Когда ширина экрана <= 1600px
                    1600: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    // Когда ширина экрана <= 1200px
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    // Когда ширина экрана <= 900px
                    900: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    // Когда ширина экрана <= 600px
                    600: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                    },
                },
            });
        </script>


    </section>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
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