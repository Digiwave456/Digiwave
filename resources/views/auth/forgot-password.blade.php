<x-guest-layout>
    <div class="container">
    <!-- Статус сессии -->
    <x-auth-session-status class="mb-4 status_forgot" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="form-container">
        @csrf

        <!-- Адрес электронной почты -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Электронная почта')" class="form-label" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>
        <div class="mb-4 about__text-block">
        <h5>{{ __('Забыли пароль? Не беда. Просто укажите ваш адрес электронной почты, и мы отправим вам ссылку для сброса пароля, которая позволит вам выбрать новый.') }}</h5>
    </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="form-button">
                {{ __('Отправить ссылку для сброса пароля') }}
            </x-primary-button>
        </div>
        </div>
        
    </form>

    <!-- Модальное окно -->
    <div id="passwordResetModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h5>Сброс пароля</h5>
            <p>Мы отправили ссылку для сброса пароля на вашу электронную почту.</p>
        </div>
    </div>
</x-guest-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('passwordResetModal');
    var closeBtn = document.querySelector('.modal-close');

    // Проверка статуса сессии и отображение модального окна
    var status = "{{ session('status') }}";
    if (status === "passwords.sent") {
        modal.style.display = 'flex';
    }

    // Закрытие модального окна
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>

