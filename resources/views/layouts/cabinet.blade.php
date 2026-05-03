<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="_userId" content="{{ auth()->id() }}">
    <meta name="_userName" content="{{ auth()->user()->name }}">

    <!-- Styles -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cabinet/common.css') }}" rel="stylesheet">
    @stack('custom_css')

    <!-- Scripts -->
    <script src="{{ asset("vendor/jquery/jquery.min.js") }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="icon" href="{{ config('app.url') }}/img/favicon.png?v=1">

    <title>{{ config('app.brand', 'Laravel') }}</title>
</head>
<body>
<div id="app">

    <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route(config('cabinet.brand_route', 'cabinet.index')) }}">
                <b>{{ config('app.brand', 'Laravel') }}</b>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">

                    @if (Route::has('cabinet.index') && isMenuItemVisible('home'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cabinet.index') }}">
                                {{ __('Home') }}
                            </a>
                        </li>
                    @endif

                    @if (Route::has('cabinet.example') && isMenuItemVisible('example'))
                        <li class="nav-item ml-3">
                            <a class="nav-link" href="{{ route('cabinet.example') }}">
                                {{ __('Example') }}
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown ml-3">

                        <a href="#" role="button" class="nav-link dropdown-toggle" data-toggle="dropdown"
                           aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu">

                            @if (Route::has('cabinet.user.profile.index'))
                                <a class="dropdown-item" href="{{ route('cabinet.user.profile.index') }}">
                                    {{ __('Profile') }}
                                </a>
                            @endif

                            @if (Route::has('cabinet.user.settings.password.index'))
                                <a class="dropdown-item" href="{{ route('cabinet.user.settings.password.index') }}">
                                    {{ __('Settings') }}
                                </a>
                            @endif

                            <div class="dropdown-divider"></div>

                            @if (Route::has('cabinet.logout'))
                                <a class="dropdown-item" href="{{ route('cabinet.logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('cabinet.logout') }}" method="POST"
                                      class="d-none">
                                    @csrf
                                </form>
                            @endif

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main role="main" class="py-md-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')

</body>
</html>
