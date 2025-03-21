<x-guest-layout>
    <!-- Session Status -->
    <!-- <div class="alert alert-success mb-4" role="alert">
        <x-auth-session-status :status="session('status')" />
    </div> -->

    <form method="POST" action="{{ route('login') }}" class="form-container">
        @csrf

        <!-- Login -->
        <div class="form-group">
            <label for="login" class="form-label">{{ __('Логин') }}</label>
            <input id="login" type="text" name="login" class="form-input" value="{{ old('login') }}" required autofocus autocomplete="username" />
            @error('login')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Пароль') }}</label>
            <input id="password" type="password" name="password" class="form-input" required autocomplete="current-password" />
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group form-remember">
            <label for="remember_me" class="form-checkbox-label">
                <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                {{ __('Запомнить меня') }}
            </label>
        </div>

        <!-- Actions -->
        <div class="form-footer">
            @if (Route::has('password.request'))
                <a class="form-link" href="{{ route('password.request') }}">
                    {{ __('Забыли пароль?') }}
                </a>
            @endif
            <button type="submit" class="form-button">{{ __('Войти') }}</button>
        </div>
    </form>
</x-guest-layout>
