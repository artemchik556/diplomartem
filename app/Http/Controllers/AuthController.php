<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Генерируем и сохраняем токен
            $token = Str::random(60);
            $request->session()->put('auth_token', $token);
            
            return redirect()->route('home')
                ->withCookie(cookie('auth_token', $token, 120))
                ->with('success', 'Вход выполнен успешно!');
        }

        return back()->withErrors(['email' => 'Неверные учетные данные'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        
        // Генерируем и сохраняем токен
        $token = Str::random(60);
        $request->session()->put('auth_token', $token);
        
        return redirect()->route('home')
            ->withCookie(cookie('auth_token', $token, 120))
            ->with('success', 'Регистрация успешна!');
    }

    public function logout(Request $request)
    {
        // Удаляем токен из сессии
        $request->session()->forget('auth_token');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Удаляем куки
        return redirect()->route('home')->withCookie(cookie()->forget('auth_token'));
    }
}