<link rel="stylesheet" href="{{ asset('css/regist.css') }}">
<div id="login-popup" class="popup">
    <div class="popup-content">
        <h2>Вход</h2>
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <input type="password" name="password" placeholder="Введите пароль" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a href="#" class="reg" onclick="switchPopup('login-popup', 'register-popup')">Зарегистрироваться</a></p>
    </div>
</div>