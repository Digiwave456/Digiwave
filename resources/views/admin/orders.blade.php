@extends('layouts.admin')
@section('content')
    <div class="order__filters">
        <a href="?filter=new">Новые</a>
        <a href="?filter=confirmed">Потвержденные</a>
        <a href="?filter=canceled">Отмененные</a>
        <a href="/orders">Показать все</a>
    </div>
    <table class="order__table table container">
        <thead>
        <tr>
            <th>ФИО клиента</th>
            <th>Товары в заказе</th>
            <th>Дата создания</th>
            <th>Итог сумма</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr class="order__raw">
                <td>{{ $order->name }}</td>
                <td>
                    <div class="order__products">
                        @foreach($order->products as $product)
                        <div class="order__product">
                            {{ $product->title }} x{{ $product->qty }}: {{$product->price * $product->qty}} руб.
                        </div>
                        @endforeach
                        Всего товаров: {{ $order->totalQty }}
                    </div>
                </td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->totalPrice }}</td>
                <td>{{ $order->status }}</td>
                <td class="">
                    <form action="/order-status/confirm/{{ $order->number }}" method="post">
                        @method('patch')
                        @csrf
                        <input type="submit" class="btn btn-success" value="Потвердить">
                    </form>
                    <form action="/order-status/cancel/{{ $order->number }}" method="post">
                        @method('patch')
                        @csrf
                        <input type="submit" class="btn btn-danger" value="Отменить">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
