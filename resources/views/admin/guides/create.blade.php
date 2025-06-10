<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container">
    <h1>Добавить нового гида</h1>
    
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

    <form class="gid" action="{{ route('admin.guides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="name" class="form-label">Имя гида</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="position" class="form-label">Должность</label>
            <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}" required>
            @error('position')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Фотография</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Стаж работы (в годах)</label>
            <input type="number" class="form-control" id="experience" name="experience" value="{{ old('experience') }}" min="0" step="1">
            @error('experience')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Добавить гида</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>