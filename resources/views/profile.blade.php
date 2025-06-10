@extends('layouts.app')

@section('content')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Личный кабинет</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-info">
                                <h3>Информация о пользователе</h3>
                                <p><strong>Имя:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="bookings-section">
                                <h3>Мои бронирования</h3>
                                @if($user->bookings->isEmpty())
                                    <p>У вас пока нет бронирований.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Экскурсия</th>
                                                    <th>Группа</th>
                                                    <th>Количество человек</th>
                                                    <th>День тура</th>
                                                    <th>Статус</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->bookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->excursion->title }}</td>
                                                    <td>
                                                        @switch($booking->group_type)
                                                            @case('a')
                                                                Группа A
                                                                @break
                                                            @case('b')
                                                                Группа B
                                                                @break
                                                            @case('c')
                                                                Группа C
                                                                @break
                                                            @default
                                                                {{ $booking->group_type }}
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $booking->number_of_people }} чел.</td>
                                                    <td>{{ $booking->booking_date ? $booking->booking_date->format('d.m.Y') : 'Не указана' }}</td>
                                                    <td>
                                                        @switch($booking->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">В обработке</span>
                                                                @break
                                                            @case('confirmed')
                                                                <span class="badge bg-success">Подтверждено</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="badge bg-info">Завершено</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="badge bg-danger">Отменено</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="reviews-section mt-4">
                                <h3>Мои отзывы</h3>
                                @if($user->reviews->isEmpty())
                                    <p>У вас пока нет отзывов.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Экскурсия</th>
                                                    <th>Оценка</th>
                                                    <th>Отзыв</th>
                                                    <th>Статус</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->reviews as $review)
                                                <tr>
                                                    <td>{{ $review->excursion->title }}</td>
                                                    <td>
                                                        <div class="rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </td>
                                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                                    <td>
                                                        @if($review->is_approved)
                                                            <span class="badge bg-success">Одобрен</span>
                                                        @else
                                                            <span class="badge bg-warning">В обработке</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 