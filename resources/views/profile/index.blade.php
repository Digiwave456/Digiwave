@extends('layouts.app')

@section('content')
    <div class="user">
        <div class="container">
            
              <div class="data__profile">
                <img src="{{ Vite::asset('resources/media/images/pajamas_profile.svg') }}" 
                     alt="Профиль" 
                     class="profile-image">
                     <div class="user-profile">
                <h2 class="profile-title">
                   <p> {{ Auth::user()->name }}</p><p> {{ Auth::user()->surname }}</p>
                </h2>
            </div>
</div>

            @if(count($orders) > 0)
                <div class="user-orders">
                    @foreach($orders as $order)
                        <div class="order__item">
                            <div class="order__number">
                                Заказ: <code>{{ $order->number }}</code>
                            </div>
                            <div class="order__status">
                                <span class="status-label">{{ $order->status }}</span>
                            </div>

                            @if($order->status == 'Новый')
                                <form action="/order-delete/{{ $order->number }}" 
                                      method="post" 
                                      class="order__delete-form">
                                    @method('delete')
                                    @csrf
                                    <input type="submit" 
                                           class="btn btn-danger" 
                                           value="Удалить заказ">
                                </form>
                            @endif

                            <div class="order__products">
                                <h4>Товары в заказе:</h4>
                                <div class="order-products-list">
                                    @foreach($order->products as $product)
                                        <div class="order-product">
                                            <span class="product-title">{{ $product->title }}</span>
                                            <div class="product-info">
                                                <span class="product-qty">x{{ $product->qty }}</span>:
                                                <span class="product-price">{{ $product->price * $product->qty }} ₽</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="order__summary">
                                    Всего товаров: <strong>{{ $order->totalQty }} шт.</strong>
                                </div>
                            </div>

                            <div class="order__total">
                                К оплате: <strong>{{ $order->totalPrice }} ₽</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <h3 class="text-center" style="color: #d1d1d1;">
                    Здесь будут отображаться заказы
                </h3>
            @endif

            <form method="POST" 
                  action="{{ route('logout') }}" 
                  class="user-logout">
                @csrf
                <button type="submit" class="form-button">
                    Выйти
                </button>
            </form>
        </div>
    </div>
@endsection