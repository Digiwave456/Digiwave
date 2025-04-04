@extends('layouts.app')
@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('messages.order.create') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.order.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('messages.order.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('messages.order.phone') }}</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('messages.order.address') }}</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">{{ __('messages.order.comment') }}</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('messages.order.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('messages.order.summary') }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                @foreach($cart as $item)
                                <tr>
                                    <td>{{ $item->title }} x {{ $item->qty }}</td>
                                    <td class="text-end">{{ number_format($item->price * $item->qty, 0, ',', ' ') }} ₽</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><strong>{{ __('messages.order.total') }}</strong></td>
                                    <td class="text-end"><strong>{{ number_format($total, 0, ',', ' ') }} ₽</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
