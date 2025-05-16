<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container">
    <h1>Список гидов</h1>
    <a href="{{ route('admin.guides.create') }}" class="btn btn-primary">Добавить гида</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($guides->isEmpty())
        <p>Гидов пока нет.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th>Фото</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guides as $guide)
                <tr>
                    <td>{{ $guide->name }}</td>
                    <td>{{ $guide->position }}</td>
                    <td>
                        @if ($guide->image)
                            <img src="{{ asset('storage/' . $guide->image) }}" width="50" alt="{{ $guide->name }}">
                        @else
                            Нет фото
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.guides.edit', $guide->id) }}" class="btn btn-primary">Редактировать</a>
                        <form action="{{ route('admin.guides.destroy', $guide->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить гида?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>