

@section('content')
<div class="container">
    <h2>Администрирование</h2>
    <ul>
        <li><a href="{{ route('admin.excursions.index') }}">Список экскурсий</a></li>
        <li><a href="{{ route('admin.excursions.create') }}">Добавить экскурсию</a></li>
        <li><a href="javascript:history.back()">Назад</a></li>
    </ul>
</div>
@endsection
