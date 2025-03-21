@extends('layouts.app')
@section('content')

<div class="container">
    <div class="cart">
        <h3 class="about__title text-start mb-4">Оформление заказа</h3>
        
        <table class="cart__table">
            <thead>
            <tr>
                <th>Название</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Итого</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart as $item)
                <tr class="cart__raw">
                    <td>{{$item->title}}</td>
                    <td class="cart__qty">
                        <span class="cart__qty-value">
                            {{ $item->qty }}
                        </span>
                    </td>
                    <td>{{$item->price}} ₽</td>
                    <td>{{$item->price * $item->qty}} ₽</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        
        <div class="order-total">
            К оплате: <span class="order-total__price">{{ $total }} ₽</span>
        </div>
        
        <form class="order-form">
            @csrf
            <div class="form-group">
                <input type="password" 
                       id="password" 
                       class="form-input" 
                       name="password" 
                       placeholder="Введите пароль" 
                       required>
            </div>
            <button type="button" id="submitBtn" class="form-button">
                Сформировать заказ
            </button>
        </form>
    </div>
</div>

<!-- Подключаем скрипты -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#submitBtn').click(function() {
        var password = $('#password').val();
        
        $.post('/create-order', {
            password: password,
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            Swal.fire({
                title: 'Успех!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK',
                background: '#1c1c1e',
                color: '#ffffff',
                confirmButtonColor: '#34c38f'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/user';
                }
            });
        })
        .fail(function() {
            Swal.fire({
                title: 'Ошибка!',
                text: 'Неверный пароль. Пожалуйста, проверьте правильность введенного пароля.',
                icon: 'error',
                background: '#1c1c1e',
                color: '#ffffff',
                confirmButtonColor: '#34c38f'
            });
        });
    });
});
</script>

@endsection
