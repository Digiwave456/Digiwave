@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ __('messages.products.title') }}</h1>
            </div>
        </div>

        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if(file_exists(public_path('resources/media/images/' . $product->img)))
                            <img src="{{ asset('resources/media/images/' . $product->img) }}" class="card-img-top" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('resources/media/images/no-image.jpg') }}" class="card-img-top" alt="{{ __('messages.product.not_found') }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>{{ __('messages.products.price') }}:</strong> {{ number_format($product->price, 0, ',', ' ') }} ₽</p>
                            <p class="card-text"><strong>{{ __('messages.products.stock') }}:</strong> {{ $product->stock }}</p>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="number" name="qty" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="submit" class="btn btn-primary">{{ __('messages.products.add_to_cart') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection 