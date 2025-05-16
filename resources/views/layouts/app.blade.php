<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Твой Тур')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('public/css/profile.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    @yield('styles')
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

    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html> 