<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container">
    <h1>Добавить новую экскурсию</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (!isset($guides) || $guides->isEmpty())
        <div class="alert alert-warning">
            Для добавления экскурсии необходимо сначала добавить хотя бы одного гида.
            <a href="{{ route('admin.guides.create') }}" class="btn btn-primary mt-2">Добавить гида</a>
        </div>
    @else
        <form action="{{ route('admin.excursions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Название экскурсии</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Цена</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Дата и время начала</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                @error('start_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Дата и время окончания</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Местоположение</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                @error('location')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Изображение (превью)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png" required>
                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="detail_image" class="form-label">Детальное изображение</label>
                <input type="file" class="form-control" id="detail_image" name="detail_image" accept="image/jpeg,image/png">
                @error('detail_image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="guide_id" class="form-label">Гид</label>
                <select class="form-control" id="guide_id" name="guide_id" required>
                    <option value="">Выберите гида</option>
                    @forelse($guides as $guide)
                        <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                            {{ $guide->name }} ({{ $guide->position }})
                        </option>
                    @empty
                        <option value="" disabled>Гидов пока нет</option>
                    @endforelse
                </select>
                @error('guide_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="transport_car" class="form-label">Как добраться на машине</label>
                <textarea class="form-control" id="transport_car" name="transport_car" rows="2">{{ old('transport_car') }}</textarea>
                @error('transport_car')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="transport_bus" class="form-label">Как добраться на автобусе</label>
                <textarea class="form-control" id="transport_bus" name="transport_bus" rows="2">{{ old('transport_bus') }}</textarea>
                @error('transport_bus')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="transport_train" class="form-label">Как добраться на поезде</label>
                <textarea class="form-control" id="transport_train" name="transport_train" rows="2">{{ old('transport_train') }}</textarea>
                @error('transport_train')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="preparation_level" class="form-label">Уровень подготовки</label>
                <textarea class="form-control" id="preparation_level" name="preparation_level" rows="2" required>{{ old('preparation_level') }}</textarea>
                @error('preparation_level')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="group_a_seats" class="form-label">Места в группе A</label>
                <input type="number" class="form-control" id="group_a_seats" name="group_a_seats" min="1" value="{{ old('group_a_seats') }}" required>
                @error('group_a_seats')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="group_b_seats" class="form-label">Места в группе B</label>
                <input type="number" class="form-control" id="group_b_seats" name="group_b_seats" min="1" value="{{ old('group_b_seats') }}" required>
                @error('group_b_seats')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="group_c_seats" class="form-label">Места в группе C</label>
                <input type="number" class="form-control" id="group_c_seats" name="group_c_seats" min="1" value="{{ old('group_c_seats') }}" required>
                @error('group_c_seats')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Добавить экскурсию</button>
            <a href="{{ route('admin.excursions.index') }}" class="btn btn-secondary">Отмена</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-info">Назад к панели</a>
        </form>
    @endif
</div>