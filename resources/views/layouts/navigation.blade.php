<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow">
    <div class="container-fluid container">
        <a class="navbar-brand fw-bold fs-4" href="/"> <img src="{{ Vite::asset('resources/media/logo/DigiWave.png') }}" style="width:200px;" ></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ route('about') }}">О нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('where') ? 'active' : '' }}" href="{{ route('where') }}">Где нас найти?</a>
                </li>
            </ul>
            @guest
                <div class="d-flex gap-3">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">Зарегистрируйтесь</a>
                    
                    <a class="btn btn-outline-success btn-sm" href="{{ route('login') }}">Войдите</a>
                </div>
            @endguest
            @auth
                <div class="d-flex gap-3">
                    <a class="btn btn-outline-light btn-sm {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user') }}">Профиль</a>
                    <a class="btn btn-outline-success btn-sm {{ Request::is('cart') ? 'active' : '' }}" href="{{ route('cart') }}">Корзина</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
