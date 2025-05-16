<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/excurs.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            @auth
            <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="user">{{ Auth::user()->name }}</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endauth
        </div>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
            </div>
        </div>

        <div class="profile-section">
            <h2><i class="fas fa-ticket-alt"></i> Мои бронирования</h2>
            @if($user->bookings->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>У вас пока нет бронирований</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th>Экскурсия</th>
                                <th>Группа</th>
                                <th>Количество человек</th>
                                <th>Дата</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->bookings as $booking)
                            <tr>
                                <td>{{ $booking->excursion->title }}</td>
                                <td>Группа {{ strtoupper($booking->group_type) }}</td>
                                <td>{{ $booking->people_count }}</td>
                                <td>{{ $booking->excursion_date ? \Carbon\Carbon::parse($booking->excursion_date)->format('d.m.Y') : 'Не указана' }}</td>
                                <td>
                                    <span class="booking-status status-{{ $booking->status }}">
                                        @switch($booking->status)
                                            @case('pending')
                                                В обработке
                                                @break
                                            @case('approved')
                                                Подтверждено
                                                @break
                                            @case('rejected')
                                                Отклонено
                                                @break
                                            @default
                                                {{ $booking->status }}
                                        @endswitch
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="profile-section">
            <h2><i class="fas fa-comments"></i> Мои отзывы</h2>
            @if($user->reviews->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-comment-slash"></i>
                    <p>Вы еще не оставили ни одного отзыва</p>
                </div>
            @else
                <div class="reviews-grid">
                    @foreach($user->reviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-excursion">{{ $review->excursion->title }}</div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="review-content">{{ $review->comment }}</div>
                        <div class="review-date">
                            <i class="far fa-calendar-alt"></i>
                            {{ $review->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>