@php
    $currentRoute = Route::currentRouteName();
@endphp
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow">
    <div class="container-fluid container">
        <a class="navbar-brand fw-bold fs-4" href="/"> <img src="{{ Vite::asset('resources/media/logo/DigiWave.png') }}" style="width:200px;" ></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('messages.navigation.toggle') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ $currentRoute === 'about' ? 'active' : '' }}" href="{{ route('about') }}">{{ __('messages.navigation.about') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentRoute === 'catalog' ? 'active' : '' }}" href="{{ route('catalog') }}">{{ __('messages.navigation.catalog') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentRoute === 'where' ? 'active' : '' }}" href="{{ route('where') }}">{{ __('messages.navigation.where') }}</a>
                </li>
            </ul>
            @guest
                <div class="d-flex gap-3">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">{{ __('messages.auth.register') }}</a>
                    <a class="btn btn-outline-success btn-sm" href="{{ route('login') }}">{{ __('messages.auth.login') }}</a>
                </div>
            @endguest
            @auth
                <div class="d-flex gap-3">
                    <a class="btn btn-outline-light btn-sm {{ $currentRoute === 'user' ? 'active' : '' }}" href="{{ route('user') }}">{{ __('messages.navigation.profile') }}</a>
                    <a class="btn btn-outline-success btn-sm {{ $currentRoute === 'cart' ? 'active' : '' }}" href="{{ route('cart') }}">{{ __('messages.navigation.cart') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('messages.auth.logout') }}</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>
