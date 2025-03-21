<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <section class="catalog container pt-4">
    <div class="catalog_text">
        <h3 class="about__title text-start">Сортировать по</h3>
        <div class="catalog__sort">
            <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'country' ? '_desc' : '' }}=country" class="catalog__sort-item
            {{ (request()->query('sort_by') == 'country' ? 'active' : request()->query('sort_by_desc') == 'country') ? 'active' : '' }}">Страна поставщика</a>
            <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'title' ? '_desc' : '' }}=title" class="catalog__sort-item
            {{ (request()->query('sort_by') == 'title' ? 'active' : request()->query('sort_by_desc') == 'title') ? 'active' : '' }}">Название</a>
            <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'price' ? '_desc' : '' }}=price" class="catalog__sort-item
            {{ (request()->query('sort_by') == 'price' ? 'active' : request()->query('sort_by_desc') == 'price') ? 'active' : '' }}">Цена</a>
            <a href="/catalog" class="catalog__sort-item--default">Сбросить</a>
        </div>
        </div>
        <div class="catalog__filter">
           @foreach($categories as $category)
               <a href="{{ $params->has('sort_by') ? '?sort_by=' . $params['sort_by'] . '&' : '?' }} filter={{ $category->id }}" class="catalog__filter-item {{ request()->query('filter') == $category->id ? 'active' : '' }}">{{ $category->product_type }}</a>
            @endforeach
        </div>
        <div class="catalog__list">
            @if(count($products) > 0)
                @foreach($products as $product)
                    <div class="card catalog__item">
                        <img src="{{ Vite::asset('resources/media/images/') . $product->img }}" alt="{{ $product->title }}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ $product->price }} руб.</p>
                            <div class="new-product__actions">
                                <a href="/product/{{ $product->id }}" class="btn btn-outline-success">Подробнее</a>
                                @auth
                                    <button onclick="addToCart({{ $product->id }})" class="btn btn-success">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h3>Ничего не найдено</h3>
            @endif
        </div>
    </section>

    @auth
<div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Успешно</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-success text-white">
            Товар успешно добавлен в корзину
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Ошибка</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-danger text-white">
            Товара нет в наличии
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch(`/add-to-cart/${productId}`)
        .then(response => {
            if (response.ok) {
                const toast = new bootstrap.Toast(document.getElementById('successToast'), {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
            } else {
                const toast = new bootstrap.Toast(document.getElementById('errorToast'), {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
            }
        })
        .catch(() => {
            const toast = new bootstrap.Toast(document.getElementById('errorToast'), {
                autohide: true,
                delay: 3000
            });
            toast.show();
        });
}
</script>

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
@endauth

@endsection
