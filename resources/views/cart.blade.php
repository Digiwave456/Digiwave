@extends('layouts.app')
@section('content')
    <div class="cart container">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (count($cart) > 0)
            <table class="cart__table">
                <tbody>
                    @foreach($cart as $item)
                    <tr class="cart__raw" data-cart-id="{{ $item->id }}">
                        <td>
                            @if(file_exists(public_path('resources/media/images/' . $item->product->img)))
                                <img src="{{ asset('resources/media/images/' . $item->product->img) }}" alt="{{ $item->product->title }}" class="card-img-top">
                            @else
                                <img src="{{ asset('resources/media/images/no-image.jpg') }}" alt="{{ __('messages.product.not_found') }}" class="card-img-top">
                            @endif
                        </td>
                        
                        <td class="cart__qty">
                        {{$item->product->title}}
                        <span class="cart__qty1">
                            <button class="btn increase-btn {{ $item->qty == $item->product->qty ? 'disabled' : '' }}" data-cart-id="{{ $item->id }}">+</button>
                            <span class="cart__qty-value" data-cart-id="{{ $item->id }}">{{ $item->qty }}</span>
                            <button class="btn decrease-btn" data-cart-id="{{ $item->id }}">-</button>
                        </span>
                        </td>
                        
                        <td class="cart__price" data-cart-id="{{ $item->id }}">{{ number_format($item->product->price * $item->qty, 0, ',', ' ') }} ₽</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="cart__actions mt-4">
                <a href="{{route('create-order')}}" class="btn btn-success">{{ __('messages.order.create') }}</a>
            </div>
        @else
            <h3 class="cart__table--empty">{{ __('messages.cart.empty') }}</h3>
        @endif
    </div>

    <div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">{{ __('messages.success') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-success text-white">
                {{ __('messages.cart.quantity_updated') }}
            </div>
        </div>
        
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">{{ __('messages.error') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-danger text-white">
                {{ __('messages.cart.quantity_error') }}
            </div>
        </div>
    </div>

    @if(count($cart) > 0)
        @push('meta')
            <meta name="csrf-token" content="{{ csrf_token() }}">
        @endpush
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = new bootstrap.Toast(document.getElementById('successToast'));
            const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

            // Функция для обновления цены товара
            function updatePrice(cartId, qty) {
                const priceElement = document.querySelector(`.cart__price[data-cart-id="${cartId}"]`);
                const qtyElement = document.querySelector(`.cart__qty-value[data-cart-id="${cartId}"]`);
                
                if (priceElement && qtyElement) {
                    qtyElement.textContent = qty;
                    
                    // Получаем текущую цену и количество
                    const currentTotal = parseFloat(priceElement.textContent.replace(/[^\d]/g, ''));
                    const currentQty = parseInt(qtyElement.textContent);
                    const unitPrice = currentTotal / currentQty;
                    
                    // Вычисляем новую цену
                    const newTotal = unitPrice * qty;
                    priceElement.textContent = new Intl.NumberFormat('ru-RU').format(newTotal) + ' ₽';
                }
            }

            // Функция для обработки изменения количества
            function handleQuantityChange(action, cartId) {
                fetch(`/changeqty/${action}/${cartId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        document.querySelector('#errorToast .toast-body').textContent = data.error;
                        errorToast.show();
                        if (data.reload) {
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    } else {
                        // Обновляем количество и цену
                        if (action === 'decr') {
                            const qtyElement = document.querySelector(`.cart__qty-value[data-cart-id="${cartId}"]`);
                            if (qtyElement && parseInt(qtyElement.textContent) <= 1) {
                                const row = document.querySelector(`.cart__raw[data-cart-id="${cartId}"]`);
                                if (row) {
                                    row.remove();
                                    
                                    if (document.querySelectorAll('.cart__raw').length === 0) {
                                        document.querySelector('.cart__table').remove();
                                        document.querySelector('.cart__actions').remove();
                                        const emptyMessage = document.createElement('h3');
                                        emptyMessage.className = 'cart__table--empty';
                                        emptyMessage.textContent = '{{ __("messages.cart.empty") }}';
                                        document.querySelector('.cart.container').appendChild(emptyMessage);
                                    }
                                }
                            } else {
                                updatePrice(cartId, parseInt(qtyElement.textContent) - 1);
                            }
                        } else {
                            // Увеличиваем количество
                            const qtyElement = document.querySelector(`.cart__qty-value[data-cart-id="${cartId}"]`);
                            if (qtyElement) {
                                updatePrice(cartId, parseInt(qtyElement.textContent) + 1);
                            }
                        }
                        
                        successToast.show();
                    }
                })
                .catch(error => {
                    document.querySelector('#errorToast .toast-body').textContent = '{{ __("messages.cart.quantity_error") }}';
                    errorToast.show();
                });
            }

            // Обработчики для кнопок увеличения количества
            document.querySelectorAll('.increase-btn').forEach(button => {
                button.addEventListener('click', () => {
                    if (!button.classList.contains('disabled')) {
                        handleQuantityChange('incr', button.dataset.cartId);
                    }
                });
            });

            // Обработчики для кнопок уменьшения количества
            document.querySelectorAll('.decrease-btn').forEach(button => {
                button.addEventListener('click', () => {
                    handleQuantityChange('decr', button.dataset.cartId);
                });
            });
        });
    </script>
    @endpush

    <style>
        .toast {
            min-width: 300px;
            background: transparent;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            position: relative;
            right: 0;
            opacity: 1 !important;
        }

        .toast-header {
            border-bottom: none;
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
        }

        .toast-body {
            padding: 12px 15px;
            border-radius: 0 0 8px 8px;
        }

        .btn-close {
            opacity: 0.8;
            padding: 12px;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* Анимация появления */
        .toast.showing {
            opacity: 1 !important;
            transform: translateX(0);
            transition: all 0.3s ease;
        }

        .toast.hide {
            opacity: 0 !important;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        /* Убираем паддинги у контейнера */
        .position-fixed {
            padding: 0;
        }

        /* Стили для кнопок */
        .increase-btn,
        .decrease-btn {
            min-width: 30px;
            height: 30px;
            padding: 0;
            line-height: 30px;
            text-align: center;
            border-radius: 4px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .increase-btn:hover:not(.disabled),
        .decrease-btn:hover {
            background-color: #e9ecef;
            border-color: #ced4da;
        }

        .increase-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .cart__qty-value {
            display: inline-block;
            min-width: 30px;
            text-align: center;
            font-weight: bold;
        }
    </style>
@endsection
