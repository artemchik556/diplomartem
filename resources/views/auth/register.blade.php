<link rel="stylesheet" href="{{ asset('css/regist.css') }}">
<div id="register-popup" class="popup">
    <div class="popup-content">
        <h2>Регистрация</h2>
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <input type="text" name="name" placeholder="Имя" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
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
            <div>
                <input type="password" name="password_confirmation" placeholder="Подтвердите пароль" required>
            </div>
            <button type="submit">Регистрация</button>
        </form>
        <p>Уже есть аккаунт? <a href="#" class="reg" onclick="switchPopup('register-popup', 'login-popup')">Войти</a></p>
    </div>
</div>