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
                    <tr class="cart__raw">
                        <td>
                            @if(file_exists(public_path('resources/media/images/' . $item->img)))
                                <img src="{{ asset('resources/media/images/' . $item->img) }}" alt="{{ $item->title }}" class="card-img-top">
                            @else
                                <img src="{{ asset('resources/media/images/no-image.jpg') }}" alt="{{ __('messages.product.not_found') }}" class="card-img-top">
                            @endif
                        </td>
                        
                        <td class="cart__qty">
                        {{$item->title}}
                        <span class="cart__qty1">
                            <button class="btn {{ $item->qty == $item->limit ? 'disabled' : '' }}" id="increase" cartid="{{ $item->id }}">+</button>
                            <span class="cart__qty-value">{{ $item->qty }}</span>
                            <button class="btn" id="decrease" cartid="{{ $item->id }}">-</button>
                        </span>
                        </td>
                        
                        <td>{{ number_format($item->price * $item->qty, 0, ',', ' ') }} ₽</td>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing cart quantity change handlers...');
            
            const cartRaws = document.querySelectorAll('.cart__raw');
            console.log('Found cart rows:', cartRaws.length);
            
            const successToast = new bootstrap.Toast(document.getElementById('successToast'));
            const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

            cartRaws.forEach((raw, index) => {
                console.log(`Processing cart row ${index + 1}`);
                
                const increase = raw.querySelector('#increase');
                const decrease = raw.querySelector('#decrease');
                const qtyValue = raw.querySelector('.cart__qty-value');
                const cartId = Number(increase.attributes.cartid.value);
                
                console.log(`Cart item ID: ${cartId}`);

                increase.addEventListener('click', () => {
                    console.log(`Increasing quantity for cart item ${cartId}`);
                    fetch(`/changeqty/incr/${cartId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.error) {
                            document.querySelector('#errorToast .toast-body').textContent = data.error;
                            errorToast.show();
                            if (data.reload) {
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.querySelector('#errorToast .toast-body').textContent = '{{ __("messages.cart.quantity_error") }}';
                        errorToast.show();
                    });
                });

                decrease.addEventListener('click', () => {
                    console.log(`Decreasing quantity for cart item ${cartId}`);
                    fetch(`/changeqty/decr/${cartId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.error) {
                            document.querySelector('#errorToast .toast-body').textContent = data.error;
                            errorToast.show();
                            if (data.reload) {
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.querySelector('#errorToast .toast-body').textContent = '{{ __("messages.cart.quantity_error") }}';
                        errorToast.show();
                    });
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
    </style>
@endsection
