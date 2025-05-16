@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Точки маршрута</h2>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Координаты</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routePoints as $point)
                                <tr>
                                    <td>{{ $point->name }}</td>
                                    <td>{{ $point->description }}</td>
                                    <td>{{ $point->coordinates }}</td>
                                    <td>
                                        <a href="{{ route('admin.route-points.show', $point->id) }}" class="btn btn-info btn-sm">
                                            Просмотр
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 