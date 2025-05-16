<!DOCTYPE html>
<html>

<head>
    <title>Заявки на консультацию</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-select {
            width: 150px;
            display: inline-block;
        }

        .actions {
            white-space: nowrap;
        }

        .dar {
            background-color: rgb(214, 197, 197);
        }
    </style>
</head>

<body>
    <div class="container-fluid p-4">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($consultations->isEmpty())
        <p>Заявок на консультацию пока нет.</p>
        @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultations as $consultation)
                    <tr>
                        <td>{{ $consultation->id }}</td>
                        <td>{{ $consultation->name }}</td>
                        <td>{{ $consultation->email }}</td>
                        <td>{{ $consultation->phone }}</td>
                        <td>
                            <form action="{{ route('admin.consultations.update', $consultation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="new" {{ $consultation->status === 'new' ? 'selected' : '' }}>Новая</option>
                                    <option value="processed" {{ $consultation->status === 'processed' ? 'selected' : '' }}>В обработке</option>
                                    <option value="completed" {{ $consultation->status === 'completed' ? 'selected' : '' }}>Завершена</option>
                                    <option value="cancelled" {{ $consultation->status === 'cancelled' ? 'selected' : '' }}>Отменена</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $consultation->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#consultationModal{{ $consultation->id }}">
                                Заметки
                            </button>

                            <!-- Модальное окно -->
                            <div class="modal fade" id="consultationModal{{ $consultation->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Редактирование заявки #{{ $consultation->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.consultations.update', $consultation) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Заметки</label>
                                                    <textarea name="admin_notes" class="form-control" rows="5">{{ $consultation->admin_notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.consultations.destroy', $consultation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Автоматическое закрытие alert через 5 секунд
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    new bootstrap.Alert(alert).close();
                });
            }, 5000);
        });
    </script>
</body>

</html>