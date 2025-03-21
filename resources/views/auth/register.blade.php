<x-guest-layout>
    <head>
        <meta charset="UTF-8">
    </head>
    <form method="POST" action="{{ route('register') }}" class="form-container">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Имя')" />
            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="error-message" />
        </div>

        <!-- Surname -->
        <div class="form-group">
            <x-input-label for="surname" :value="__('Фамилия')" />
            <x-text-input id="surname" class="form-input" type="text" name="surname" :value="old('surname')" required autocomplete="surname" />
            <x-input-error :messages="$errors->get('surname')" class="error-message" />
        </div>

        <!-- Patronymic -->
        <div class="form-group">
            <x-input-label for="patronymic" :value="__('Отчество')" />
            <x-text-input id="patronymic" class="form-input" type="text" name="patronymic" :value="old('patronymic')" required autocomplete="patronymic" />
            <x-input-error :messages="$errors->get('patronymic')" class="error-message" />
        </div>

        <!-- Login -->
        <div class="form-group">
            <x-input-label for="login" :value="__('Логин')" />
            <x-text-input id="login" class="form-input" type="text" name="login" :value="old('login')" required autocomplete="login" />
            <x-input-error :messages="$errors->get('login')" class="error-message" />
        </div>

        <!-- Email -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Пароль')" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />
            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
        </div>

        <!-- Rules Confirmation -->
        <div class="form-group checkbox-group">
            <x-text-input id="rules_confirmation" class="form-checkbox" type="checkbox" name="rules_confirmation" required />
            <x-input-label for="rules_confirmation" :value="__('Согласие с правилами регистрации')" class="checkbox-label" />
        </div>

        <div class="form-footer">
            <a class="form-link" href="{{ route('login') }}">
                {{ __('Уже зарегистрированы?') }}
            </a>
            <x-primary-button class="form-button">
                {{ __('Зарегистрироваться') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
