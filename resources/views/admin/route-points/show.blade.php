@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Просмотр точки маршрута</h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Основная информация</h4>
                            <table class="table">
                                <tr>
                                    <th>Название:</th>
                                    <td>{{ $routePoint->name }}</td>
                                </tr>
                                <tr>
                                    <th>Описание:</th>
                                    <td>{{ $routePoint->description }}</td>
                                </tr>
                                <tr>
                                    <th>Координаты:</th>
                                    <td>{{ $routePoint->coordinates }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Дополнительная информация</h4>
                            <table class="table">
                                <tr>
                                    <th>Дата создания:</th>
                                    <td>{{ $routePoint->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Последнее обновление:</th>
                                    <td>{{ $routePoint->updated_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.route-points.index') }}" class="btn btn-secondary">
                            Назад к списку
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 