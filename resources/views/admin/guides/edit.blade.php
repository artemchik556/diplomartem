<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container">
    <h1>Редактирование гида</h1>
    
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

    <form class="gid" action="{{ route('admin.guides.update', $guide->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="name" class="form-label">Имя гида</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $guide->name) }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="position" class="form-label">Должность</label>
            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $guide->position) }}" required>
            @error('position')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Фотография</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
            @if ($guide->image)
                <div class="mt-3">
                    <p>Текущее фото:</p>
                    <img src="{{ asset('storage/' . $guide->image) }}" width="200" class="img-thumbnail mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                        <label class="form-check-label text-danger" for="remove_image">
                            Удалить текущее фото
                        </label>
                    </div>
                </div>
            @endif
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $guide->description) }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Стаж работы (в годах)</label>
            <input type="number" class="form-control" id="experience" name="experience" value="{{ old('experience', $guide->experience) }}" min="0" step="1">
            @error('experience')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>

@section('scripts')
<script>
    document.getElementById('remove_image')?.addEventListener('change', function() {
        if (this.checked && !confirm('Вы уверены, что хотите удалить фотографию?')) {
            this.checked = false;
        }
    });
</script>
@endsection