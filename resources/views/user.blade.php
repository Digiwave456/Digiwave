@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ __('messages.user.profile') }}</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('messages.user.personal_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>{{ __('messages.user.name') }}:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>{{ __('messages.user.email') }}:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>{{ __('messages.user.phone') }}:</strong> {{ auth()->user()->phone }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('messages.user.orders') }}</h3>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.order.number') }}</th>
                                        <th>{{ __('messages.order.date') }}</th>
                                        <th>{{ __('messages.order.status') }}</th>
                                        <th>{{ __('messages.order.total') }}</th>
                                        <th>{{ __('messages.order.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->number }}</td>
                                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                            <td>{{ __('messages.order.statuses.' . $order->status) }}</td>
                                            <td>{{ number_format($order->total, 0, ',', ' ') }} ₽</td>
                                            <td>
                                                <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-primary">{{ __('messages.order.details') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>{{ __('messages.user.no_orders') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 