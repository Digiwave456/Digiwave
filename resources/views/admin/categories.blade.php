@extends('layouts.admin')
@section('content')
        <table class="cart__table container">
            <thead>
                <th>id</th>
                <th>Название</th>
                <th>Действия</th>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="cart__raw">
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->product_type }}</td>
                        <td class="">
                            <a href="/category-edit/{{ $category->id }}" class="btn btn-primary">Редактировать</a>
                           <form action="/category-delete/{{ $category->id }}" method="post">
                               @method('delete')
                               @csrf
                               <input type="submit" class="btn btn-danger" value="Удалить">
                           </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
