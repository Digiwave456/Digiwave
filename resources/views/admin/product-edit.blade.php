@extends('layouts.app')
@section('content')
    <div class="product-edit">
        <form action="/product-update/{{ $product->id }}" method="POST">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Цена</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3" value="{{ $product->description }}"></textarea>
            </div>
            <div class="mb-3">
                <label for="qty" class="form-label">Количество</label>
                <input type="number" class="form-control" id="qty" name="qty" value="{{ $product->qty }}">
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Цвет</label>
                <input type="text" class="form-control" id="color" name="color" value="{{ $product->color }}">
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">Изображение</label>
                <input type="text" class="form-control" id="img" name="img" value="{{ $product->img }}"
                placeholder="Введите название изображения с расширением файла из resource/media/images">
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Страна-производитель</label>
                <input type="text" class="form-control" id="country" name="country" value="{{ $product->country }}">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Категория</label>
                <select name="category" id="category">
                        @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($category->product_type == $product->product_type)>{{ $category->product_type }}</option>
                        @endforeach
                </select>
            </div>
            <input type="submit" value="Потвердить">
        </form>
    </div>

@endsection
