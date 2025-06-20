<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Добавлен CSRF-токен -->
    <title>Управление экскурсиями и гидами</title>
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/info.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modul.css') }}">
    <link rel="stylesheet" href="{{ asset('css/excursion-detail.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Добавлен Font Awesome -->
    <script src="{{ asset('js/auth.js') }}" defer></script>
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

    <div class="container mt-4">
        <h1>Панель администратора</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'add-excursion' ? 'active' : '' }}" id="add-excursion-tab" data-bs-toggle="tab" data-bs-target="#add-excursion" type="button" role="tab">Добавить экскурсию</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'edit-excursion' ? 'active' : '' }}" id="edit-excursion-tab" data-bs-toggle="tab" data-bs-target="#edit-excursion" type="button" role="tab">Редактировать экскурсию</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'list-excursions' ? 'active' : '' }}" id="list-excursions-tab" data-bs-toggle="tab" data-bs-target="#list-excursions" type="button" role="tab">Список экскурсий</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'list-guides' ? 'active' : '' }}" id="list-guides-tab" data-bs-toggle="tab" data-bs-target="#list-guides" type="button" role="tab">Список гидов</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'consultations' ? 'active' : '' }}" id="consultations-tab" data-bs-toggle="tab" data-bs-target="#consultations" type="button" role="tab">Консультации</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 'reviews-bookings' ? 'active' : '' }}" id="reviews-bookings-tab" data-bs-toggle="tab" data-bs-target="#reviews-bookings" type="button" role="tab">Отзывы и бронирования</button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">
            <!-- Добавление экскурсии -->
            <div class="tab-pane fade {{ $tab == 'add-excursion' ? 'show active' : '' }}" id="add-excursion" role="tabpanel">
                <h2>Добавление новой экскурсии</h2>
                <form id="add-excursion-form" class="pad edits" action="{{ route('admin.excursions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Название*</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание*</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <h3>Гиды для групп*</h3>
                    <div class="form-group">
                        <label for="guide_a_id">Гид для группы A*</label>
                        <select class="form-control" id="guide_a_id" name="guide_a_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_a_id') == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_a_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="guide_b_id">Гид для группы B*</label>
                        <select class="form-control" id="guide_b_id" name="guide_b_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_b_id') == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_b_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="guide_c_id">Гид для группы C*</label>
                        <select class="form-control" id="guide_c_id" name="guide_c_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_c_id') == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_c_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="start_date">Дата начала*</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="end_date">Дата окончания*</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <h3>Количество мест в группах*</h3>
                    <div class="form-group">
                        <label for="group_a_seats">Группа A</label>
                        <input type="number" class="form-control" id="group_a_seats" name="group_a_seats" value="{{ old('group_a_seats') }}" min="1" required>
                        @error('group_a_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="group_b_seats">Группа B</label>
                        <input type="number" class="form-control" id="group_b_seats" name="group_b_seats" value="{{ old('group_b_seats') }}" min="1" required>
                        @error('group_b_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="group_c_seats">Группа C</label>
                        <input type="number" class="form-control" id="group_c_seats" name="group_c_seats" value="{{ old('group_c_seats') }}" min="1" required>
                        @error('group_c_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Цена (руб)*</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="location">Место проведения*</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                        @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="photos">Фотографии экскурсии (множественная загрузка)*</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <small class="form-text text-muted">Загрузите одно или несколько изображений. Первое будет использовано как превью.</small>
                        @error('photos') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_car">Как добраться на машине:</label>
                        <textarea class="form-control" id="transport_car" name="transport_car" rows="2">{{ old('transport_car') }}</textarea>
                        @error('transport_car') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_bus">Как добраться на автобусе:</label>
                        <textarea class="form-control" id="transport_bus" name="transport_bus" rows="2">{{ old('transport_bus') }}</textarea>
                        @error('transport_bus') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_train">Как добраться на поезде:</label>
                        <textarea class="form-control" id="transport_train" name="transport_train" rows="2">{{ old('transport_train') }}</textarea>
                        @error('transport_train') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_level">Уровень подготовки:</label>
                        <select class="form-control" id="preparation_level" name="preparation_level" required>
                            <option value="">Выберите уровень</option>
                            <option value="easy" {{ old('preparation_level') == 'easy' ? 'selected' : '' }}>Легкий</option>
                            <option value="medium" {{ old('preparation_level') == 'medium' ? 'selected' : '' }}>Средний</option>
                            <option value="hard" {{ old('preparation_level') == 'hard' ? 'selected' : '' }}>Сложный</option>
                        </select>
                        @error('preparation_level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить экскурсию</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Отмена</a>
                </form>
            </div>

            <!-- Редактирование экскурсии -->
            <div class="tab-pane fade {{ $tab == 'edit-excursion' ? 'show active' : '' }}" id="edit-excursion" role="tabpanel">
                <h2>Редактирование экскурсии</h2>
                @if($excursion)
                <form class="pad" id="edit-excursion-form" method="POST" action="{{ route('admin.excursions.update', $excursion->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Название*</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $excursion->title) }}" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание*</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $excursion->description) }}</textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <h3>Гиды для групп*</h3>
                    <div class="form-group">
                        <label for="guide_a_id">Гид для группы A*</label>
                        <select class="form-control" id="guide_a_id" name="guide_a_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_a_id', $excursion->guide_a_id) == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_a_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="guide_b_id">Гид для группы B*</label>
                        <select class="form-control" id="guide_b_id" name="guide_b_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_b_id', $excursion->guide_b_id) == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_b_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="guide_c_id">Гид для группы C*</label>
                        <select class="form-control" id="guide_c_id" name="guide_c_id" required>
                            @if(!isset($guides) || $guides->isEmpty())
                                <option value="">Гидов пока нет</option>
                            @else
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}" {{ old('guide_c_id', $excursion->guide_c_id) == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->position }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('guide_c_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="start_date">Дата начала*</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date', $excursion->start_date->format('Y-m-d\TH:i')) }}" required>
                        @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="end_date">Дата окончания*</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date', $excursion->end_date->format('Y-m-d\TH:i')) }}" required>
                        @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <h3>Количество мест в группах*</h3>
                    <div class="form-group">
                        <label for="group_a_seats">Группа A</label>
                        <input type="number" class="form-control" id="group_a_seats" name="group_a_seats" 
                               value="{{ old('group_a_seats', $excursion->group_a_seats) }}" min="1" required>
                        @error('group_a_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="group_b_seats">Группа B</label>
                        <input type="number" class="form-control" id="group_b_seats" name="group_b_seats" 
                               value="{{ old('group_b_seats', $excursion->group_b_seats) }}" min="1" required>
                        @error('group_b_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="group_c_seats">Группа C</label>
                        <input type="number" class="form-control" id="group_c_seats" name="group_c_seats" 
                               value="{{ old('group_c_seats', $excursion->group_c_seats) }}" min="1" required>
                        @error('group_c_seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Цена (руб)*</label>
                        <input type="number" class="form-control" id="price" name="price" 
                               value="{{ old('price', $excursion->price) }}" min="0" step="0.01" required>
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="location">Место проведения*</label>
                        <input type="text" class="form-control" id="location" name="location" 
                               value="{{ old('location', $excursion->location) }}" required>
                        @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($excursion->photos->isNotEmpty())
                        <div class="form-group">
                            <label>Текущие фотографии:</label>
                            <div class="photo-gallery">
                                @foreach($excursion->photos as $photo)
                                    <div class="photo-item">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Photo" width="150">
                                        <div class="photo-actions">
                                            <input type="checkbox" name="remove_photos[]" value="{{ $photo->id }}" id="remove_photo_{{ $photo->id }}">
                                            <label for="remove_photo_{{ $photo->id }}">Удалить</label>
                                            @if($photo->is_preview)
                                                <span class="badge bg-primary">Превью</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="photos">Добавить новые фотографии</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
                        <small class="form-text text-muted">Загрузите новые изображения. Первое будет использовано как превью, если его нет.</small>
                        @error('photos') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_car">Как добраться на машине</label>
                        <textarea class="form-control" id="transport_car" name="transport_car" rows="2">{{ old('transport_car', $excursion->transport_car) }}</textarea>
                        @error('transport_car') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_bus">Как добраться на автобусе</label>
                        <textarea class="form-control" id="transport_bus" name="transport_bus" rows="2">{{ old('transport_bus', $excursion->transport_bus) }}</textarea>
                        @error('transport_bus') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="transport_train">Как добраться на поезде</label>
                        <textarea class="form-control" id="transport_train" name="transport_train" rows="2">{{ old('transport_train', $excursion->transport_train) }}</textarea>
                        @error('transport_train') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="preparation_level">Уровень подготовки</label>
                        <select class="form-control" id="preparation_level" name="preparation_level" required>
                            <option value="">Выберите уровень</option>
                            <option value="easy" {{ old('preparation_level', $excursion->preparation_level) == 'easy' ? 'selected' : '' }}>Легкий</option>
                            <option value="medium" {{ old('preparation_level', $excursion->preparation_level) == 'medium' ? 'selected' : '' }}>Средний</option>
                            <option value="hard" {{ old('preparation_level', $excursion->preparation_level) == 'hard' ? 'selected' : '' }}>Сложный</option>
                        </select>
                        @error('preparation_level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Отмена</a>
                </form>
                @else
                <p>Выберите экскурсию для редактирования из списка.</p>
                @endif
            </div>

            <!-- Список экскурсий -->
            <div class="tab-pane fade {{ $tab == 'list-excursions' ? 'show active' : '' }}" id="list-excursions" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Список экскурсий</h3>
                    <a href="{{ route('admin.excursions.create') }}" class="btn btn-primary">Добавить экскурсию</a>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 filter-form">
                            <input type="hidden" name="tab" value="list-excursions">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Поиск по названию</label>
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Введите название экскурсии">
                            </div>
                            <div class="col-md-3">
                                <label for="min_price" class="form-label">Цена от</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}" min="0" step="100">
                            </div>
                            <div class="col-md-3">
                                <label for="max_price" class="form-label">Цена до</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}" min="0" step="100">
                            </div>
                            <div class="col-md-2">
                                <label for="sort" class="form-label">Сортировка</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="">По умолчанию</option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>По возрастанию цены</option>
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>По убыванию цены</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Применить фильтры</button>
                                <button type="button" class="btn btn-secondary reset-filters">Сбросить</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if($excursions->isEmpty())
                    <p>Экскурсий пока нет.</p>
                @else
                    <table class="table excursions-table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Гиды (A/B/C)</th>
                                <th>Дата</th>
                                <th>Места (A/B/C)</th>
                                <th>Цена</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($excursions as $excursionItem)
                            <tr>
                                <td>{{ $excursionItem->title }}</td>
                                <td>
                                    A: {{ $excursionItem->guideA ? $excursionItem->guideA->name : 'Не указан' }}<br>
                                    B: {{ $excursionItem->guideB ? $excursionItem->guideB->name : 'Не указан' }}<br>
                                    C: {{ $excursionItem->guideC ? $excursionItem->guideC->name : 'Не указан' }}
                                </td>
                                <td>
                                    {{ $excursionItem->start_date->format('d.m.Y H:i') }}<br>
                                    до {{ $excursionItem->end_date->format('d.m.Y H:i') }}
                                </td>
                                <td>
                                    {{ $excursionItem->availableSeats('a') }}/{{ $excursionItem->group_a_seats }} /
                                    {{ $excursionItem->availableSeats('b') }}/{{ $excursionItem->group_b_seats }} /
                                    {{ $excursionItem->availableSeats('c') }}/{{ $excursionItem->group_c_seats }}
                                </td>
                                <td>{{ number_format($excursionItem->price, 2) }} ₽</td>
                                <td class="actions">
                                    <a href="{{ route('admin.excursions.edit', $excursionItem->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                                    <form class="delete-form" action="{{ route('admin.excursions.destroy', $excursionItem->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Список гидов -->
            <div class="tab-pane fade {{ $tab == 'list-guides' ? 'show active' : '' }}" id="list-guides" role="tabpanel">
                <h2>Список гидов</h2>
                <a href="{{ route('admin.guides.create') }}" class="btn btn-primary">Добавить гида</a>
                @if(!isset($guides) || $guides->isEmpty())
                    <p>Гидов пока нет.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Должность</th>
                                <th>Стаж (годы)</th>
                                <th>Фото</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guides as $guideItem)
                            <tr>
                                <td>{{ $guideItem->name }}</td>
                                <td>{{ $guideItem->position }}</td>
                                <td>{{ $guideItem->experience ?? 'Не указан' }}</td>
                                <td>
                                    @if($guideItem->image)
                                        <img src="{{ asset('storage/' . $guideItem->image) }}" width="50">
                                    @else
                                        Нет фото
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.guides.edit', $guideItem->id) }}" class="btn btn-primary">Редактировать</a>
                                    <form class="delete-form" action="{{ route('admin.guides.destroy', $guideItem->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Отзывы и бронирования -->
            <div class="tab-pane fade {{ $tab == 'reviews-bookings' ? 'show active' : '' }}" id="reviews-bookings" role="tabpanel">
                <ul class="nav nav-tabs" id="reviewsBookingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Отзывы</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">Бронирования</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="reviewsBookingsTabContent">
                    <!-- Отзывы -->
                    <div class="tab-pane fade show active" id="reviews" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Управление отзывами</h3>
                        </div>
                        @if($reviews->isEmpty())
                            <p>Нет отзывов для модерации</p>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Пользователь</th>
                                            <th>Экскурсия</th>
                                            <th>Оценка</th>
                                            <th>Отзыв</th>
                                            <th>Дата</th>
                                            <th>Статус</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reviews as $review)
                                        <tr>
                                            <td>{{ $review->user->name }}</td>
                                            <td>{{ $review->excursion->title }}</td>
                                            <td>
                                                <div class="review-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'active' : 'inactive' }}"></i>
                                                    @endfor
                                                    <span class="rating-value">({{ $review->rating }} из 5)</span>
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($review->comment, 100) }}</td>
                                            <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('reviews.updateStatus', $review) }}" method="POST" class="status-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-control status-select" onchange="this.form.submit()">
                                                        <option value="pending" {{ !$review->is_approved ? 'selected' : '' }}>В обработке</option>
                                                        <option value="approved" {{ $review->is_approved ? 'selected' : '' }}>Одобрен</option>
                                                        <option value="rejected">Отклонен</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">
                                                    <i class="fas fa-eye"></i> Просмотр
                                                </button>
                                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
                                                        <i class="fas fa-trash"></i> Удалить
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Отзыв на экскурсию "{{ $review->excursion->title }}"</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Пользователь:</strong> {{ $review->user->name }}</p>
                                                        <p><strong>Дата:</strong> {{ $review->created_at->format('d.m.Y H:i') }}</p>
                                                        <p><strong>Оценка:</strong></p>
                                                        <div class="rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <p><strong>Отзыв:</strong></p>
                                                        <p>{{ $review->comment }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Бронирования -->
                    <div class="tab-pane fade" id="bookings" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Управление бронированиями</h3>
                        </div>
                        @if($bookings->isEmpty())
                            <p>Нет бронирований</p>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Пользователь</th>
                                            <th>Экскурсия</th>
                                            <th>Группа</th>
                                            <th>Количество человек</th>
                                            <th>Дата бронирования</th>
                                            <th>Статус</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->excursion->title }}</td>
                                            <td>Группа {{ strtoupper($booking->group_type) }}</td>
                                            <td>{{ $booking->number_of_people }}</td>
                                            <td>{{ $booking->booking_date->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST" class="status-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-control status-select" onchange="this.form.submit()">
                                                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>В обработке</option>
                                                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                                                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Завершено</option>
                                                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Отменено</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $booking->id }}">
                                                    <i class="fas fa-eye"></i> Просмотр
                                                </button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="bookingModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Бронирование экскурсии "{{ $booking->excursion->title }}"</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Пользователь:</strong> {{ $booking->user->name }}</p>
                                                        <p><strong>Группа:</strong> {{ strtoupper($booking->group_type) }}</p>
                                                        <p><strong>Количество человек:</strong> {{ $booking->number_of_people }}</p>
                                                        <p><strong>Дата бронирования:</strong> {{ $booking->booking_date->format('d.m.Y H:i') }}</p>
                                                        <p><strong>Статус:</strong> 
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
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Консультации -->
            <div class="tab-pane fade {{ $tab == 'consultations' ? 'show active' : '' }}" id="consultations" role="tabpanel">
                <h2>Заявки на консультацию</h2>
                <div id="consultations-content">
                    <iframe src="{{ route('admin.consultations.index') }}" frameborder="0" style="width: 100%; height: 800px;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Функция для базовой валидации формы
        function validateForm(form) {
            let isValid = true;
            $(form).find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }

        // AJAX для формы добавления экскурсии
        $('#add-excursion-form').on('submit', function(e) {
            e.preventDefault();
            if (!validateForm(this)) {
                alert('Пожалуйста, заполните все обязательные поля.');
                return;
            }
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Добавлен CSRF-токен
                },
                success: function(response) {
                    window.location.href = '{{ route('admin.dashboard') }}#list-excursions';
                },
                error: function(xhr) {
                    alert('Ошибка при добавлении экскурсии: ' + xhr.responseText);
                }
            });
        });

        // AJAX для формы редактирования экскурсии
        $('#edit-excursion-form').on('submit', function(e) {
            e.preventDefault();
            if (!validateForm(this)) {
                alert('Пожалуйста, заполните все обязательные поля.');
                return;
            }
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Добавлен CSRF-токен
                },
                success: function(response) {
                    window.location.href = '{{ route('admin.dashboard') }}#list-excursions';
                },
                error: function(xhr) {
                    alert('Ошибка при сохранении изменений: ' + xhr.responseText);
                }
            });
        });

        // AJAX для форм удаления
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Вы уверены, что хотите удалить эту экскурсию? Это действие нельзя отменить.')) {
                    this.submit();
                }
            });
        });

        // Подтверждение удаления фото
        document.querySelectorAll('input[name="remove_photos[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked && !confirm('Вы уверены, что хотите удалить фотографию?')) {
                    this.checked = false;
                }
            });
        });

        // AJAX для формы фильтрации
        $('.filter-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#list-excursions').html($(response).find('#list-excursions').html());
                    window.location.hash = 'list-excursions';
                    const tab = document.querySelector('.nav-link[data-bs-target="#list-excursions"]');
                    if (tab) {
                        const tabInstance = new bootstrap.Tab(tab);
                        tabInstance.show();
                    }
                },
                error: function(xhr) {
                    console.error('Ошибка при применении фильтров:', xhr.responseText);
                    alert('Ошибка при применении фильтров: ' + xhr.responseText);
                }
            });
        });

        // Обработчик для кнопки "Сбросить"
        $('.reset-filters').on('click', function() {
            $('.filter-form')[0].reset();
            $.ajax({
                url: '{{ route('admin.dashboard') }}',
                method: 'GET',
                data: { tab: 'list-excursions' },
                success: function(response) {
                    $('#list-excursions').html($(response).find('#list-excursions').html());
                    history.pushState(null, null, '{{ route('admin.dashboard') }}#list-excursions');
                    const tab = document.querySelector('.nav-link[data-bs-target="#list-excursions"]');
                    if (tab) {
                        const tabInstance = new bootstrap.Tab(tab);
                        tabInstance.show();
                    }
                },
                error: function(xhr) {
                    console.error('Ошибка при сбросе фильтров:', xhr.responseText);
                    alert('Ошибка при сбросе фильтров: ' + xhr.responseText);
                }
            });
        });

        // Активация вкладки при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                const tab = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
                if (tab) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }
        });
    </script>
</body>
</html>