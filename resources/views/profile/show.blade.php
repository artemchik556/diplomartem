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
            <a href="{{ route('profile') }}" class="user">{{ Auth::user()->name }}</a>
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
                <!-- Форма фильтрации -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('profile') }}" method="GET" class="row g-3 filter-form">
                            <div class="col-md-4">
                                <label for="sort_date" class="form-label">Сортировка по дате</label>
                                <select class="form-select" id="sort_date" name="sort_date">
                                    <option value="">По умолчанию</option>
                                    <option value="desc" {{ request('sort_date') == 'desc' ? 'selected' : '' }}>Новее → старее</option>
                                    <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Старее → новее</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="sort_title" class="form-label">Сортировка по названию</label>
                                <select class="form-select" id="sort_title" name="sort_title">
                                    <option value="">По умолчанию</option>
                                    <option value="asc" {{ request('sort_title') == 'asc' ? 'selected' : '' }}>А-Я</option>
                                    <option value="desc" {{ request('sort_title') == 'desc' ? 'selected' : '' }}>Я-А</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Статус</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Все</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В обработке</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Подтверждено</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершённые</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Применить</button>
                                <a href="{{ route('profile') }}" class="btn btn-secondary">Сбросить</a>
                            </div>
                        </form>
                    </div>
                </div>

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
                                <td>{{ $booking->number_of_people }}</td>
                                <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') : 'Не указана' }}</td>
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
                                            @case('completed')
                                                Завершённая
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

    <script>
    document.querySelector('.filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = '{{ route('profile') }}?' + new URLSearchParams(formData).toString();

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('.bookings-table tbody');
            tbody.innerHTML = '';
            data.bookings.forEach(booking => {
                tbody.innerHTML += `
                    <tr>
                        <td>${booking.excursion.title}</td>
                        <td>Группа ${booking.group_type.toUpperCase()}</td>
                        <td>${booking.number_of_people}</td>
                        <td>${booking.booking_date ? new Date(booking.booking_date).toLocaleDateString('ru-RU') : 'Не указана'}</td>
                        <td>
                            <span class="booking-status status-${booking.status}">
                                ${booking.status === 'pending' ? 'В обработке' :
                                  booking.status === 'approved' ? 'Подтверждено' :
                                  booking.status === 'rejected' ? 'Отклонено' :
                                  booking.status === 'completed' ? 'Завершённая' : booking.status}
                            </span>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error:', error));
    });
    </script>
</body>
</html>