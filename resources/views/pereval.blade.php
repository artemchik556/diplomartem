<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>Pereval</title>
    <link rel="stylesheet" href="{{ asset('css/pereval.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <header class="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="Твой Тур">
            </a>
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

    <section class="heros2">
        <div class="pereval-content">
            <h1 class="pereval-text">Перевал Дятлова: загадка, <br>покрытая снегом</h1>
            <div class="pereval-texts">
                <p>Один из самых загадочных мест Урала, где время словно застыло<br> Ледяной ветер, бескрайние просторы и история, окутанная тайной<br> Здесь природа хранит свои тайны, а прошлое погружается в каждый<br> порыв ветра…</p>
            </div>
        </div>
    </section>

    <div class="ag-timeline-block">
        <div class="ag-timeline_title-box">
            <div class="ag-timeline_title">Погрузись в историю Перевала Дятлова</div>
        </div>
        <section class="ag-section">
            <div class="ag-format-container">
            <div class="js-timeline ag-timeline">
                <div class="js-timeline_line ag-timeline_line">
                <div class="js-timeline_line-progress ag-timeline_line-progress"></div>
                </div>
                <div class="ag-timeline_list">
                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">23 января</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval.png" class="ag-timeline-card_img" width="640" height="300" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">23 января</div>
                        <div class="ag-timeline-card_desc">
                            В Свердловске группа туристов из девяти студентов и выпускников Уральского политехнического института под руководством Игоря Дятлова отправляется в лыжный поход по Северному Уралу. Их цель – пройти маршрут 3-й категории сложности и достичь горы Отортен (в переводе с языка манси – «не идти туда»).
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">25 января</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval2.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">25 января</div>
                        <div class="ag-timeline-card_desc">
                           Группа добирается поездом до станции Ивдель – небольшая поселка в Свердловской области. Затем туристы направляются в поселок Вижай, который становится их последней точкой перед выходом в автономный поход.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">27 января</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval3.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">27 января</div>
                        <div class="ag-timeline-card_desc">
Группа выезжает по маршруту из поселка Вижай. По пути один из участников – Юрий Юдин – думает о недостаточности (обострение ревматизма) и принимает решение вернуться. Оставшиеся девять человек продолжают экспедицию.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">28-31 января</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval4.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">28-31 января</div>
                        <div class="ag-timeline-card_desc">
                            Туристы проезжают через лесистую местность, пересекают реку Лозьву и попадают в зону горных массивов. В дневниках участников фиксируются погодные условия и их настроение: несмотря на трудности, дух в группе боевой.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">1 февраля</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval5.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">Season 9</div>
                        <div class="ag-timeline-card_desc">
Из-за ухудшения погоды и снежных заносов группа сбивается с маршрута и вместо спуска в долину реки Ауспия оказывается на склоне горы Холатчахль (в переводе с языка манси – «Гора мертвецов»). Туристы принимают решение не сразу, а разбить лагерь прямо на склоне. Это был их последний день.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">12 февраля</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval6.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">12 февраля</div>
                        <div class="ag-timeline-card_desc">
Срок, когда Дятлов должен будет отправить телеграмму о завершении похода, пройдет. Однако родственники пока не беспокоятся, полагая, что задержка связана с трудным маршрутом.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">20 февраля</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval7.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">20 февраля</div>
                        <div class="ag-timeline-card_desc">
После прослушивания родственников в районе Медового маршрута направляются поисковые группы, состоящие из студентов, спасателей и военных.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">26 февраля</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval8.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">26 февраля</div>
                        <div class="ag-timeline-card_desc">
                            Поисковики находятся в палатке на склоне горы Холатчахль. Она сильно повреждена, разрез изнутри. Внутри обнаружили вещи, еду и обувь для туристов, что говорит о том, что группа покинула лагерь в спешке.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">27 февраля - 5 марта</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval9.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">27 февраля - 5 марта</div>
                        <div class="ag-timeline-card_desc">
В 1,5 км от палатки, в кромки леса, идут тела Юрия Дорошенко и Георгия Кривонищенко. Они босые, одеты только в нижнее белье, а их тела покрыты ожогами. Рядом обнаружены остатки костра. В 300 метрах от них идут тела Игоря Дятлова, выступающего в стороне палатки. Затем находят тела Зинаиды Колмогоровой и Рустемы Слободина. Все они пытались вернуться в лагерь.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">4 мая</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        <img src="img/pereval10.png" class="ag-timeline-card_img" width="640" height="300" alt="" />
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">4 мая</div>
                        <div class="ag-timeline-card_desc">
После таяния снега в глубоком ярости, в 75 метрах от костра, находят Людмилы Дубининой, Александра Колеватова, Николая Тибо-Бриньоля и Семёна Золотарёва. Их тело сильно повреждено: у Дубининой и Тибо-Бриньоля сломаны кости, у Золотарёва – пробит череп, а у Дубининой отсутствует язык.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1959</div>
                    </div>
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">Июнь</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">Июнь</div>
                        <div class="ag-timeline-card_desc">
Официальное мнение приходит к выводу, что туристы стран Европы в результате воздействия «непреодолимой силы». Однако точная причина трагедии не установлена.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>

                <div class="js-timeline_item ag-timeline_item">
                    <div class="ag-timeline-card_box">
                    <div class="ag-timeline-card_meta-box">
                        <div class="ag-timeline-card_meta">В наше время</div>
                    </div>
                    <div class="js-timeline-card_point-box ag-timeline-card_point-box">
                        <div class="ag-timeline-card_point">1960</div>
                    </div>
                    </div>
                    <div class="ag-timeline-card_item">
                    <div class="ag-timeline-card_inner">
                        <div class="ag-timeline-card_img-box">
                        </div>
                        <div class="ag-timeline-card_info">
                        <div class="ag-timeline-card_title">В наше время</div>
                        <div class="ag-timeline-card_desc">
Происшествие остается одним из самых загадочных катастроф XX века. Высказываются следующие версии: лавина, ураган, военные испытания, воздействие местного населения, инфразвук, выбросы газа и даже паранормальные явления. Однако ни один из вариантов не был окончательно доказан.
                        </div>
                        </div>
                    </div>
                    <div class="ag-timeline-card_arrow"></div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>
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


    <script src="{{ asset('js/pereval.js')}}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>

</body>
@include('auth.login')
@include('auth.register')

</html>