<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Обработать входящий запрос.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, что пользователь аутентифицирован и является администратором
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Если не администратор — возвращаем ошибку 403
        abort(403, 'Доступ запрещен');
    }
}
