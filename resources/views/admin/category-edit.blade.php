@extends('layouts.admin')
@section('content')
    <div class="category-edit container">
        <form action="/category-update/{{ $category->id }}" method="POST">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Название категории</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $category->product_type }}">
            </div>
            <input type="submit" class="btn btn-primary" value="Подтвердить">
        </form>
    </div>
@endsection
