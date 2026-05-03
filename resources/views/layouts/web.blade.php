<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">



    <!-- Styles -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/web/common.css') }}" rel="stylesheet">
    @stack('custom_css')

    <!-- Scripts -->
    <script src="{{ asset("vendor/jquery/jquery.min.js") }}" defer></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

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
                <a class="navbar-brand" href="{{ url('/') }}">
                    <b>{{ config('app.brand', 'Laravel') }}</b>
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @auth
                            @if (Route::has('cabinet.index'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cabinet.index') }}">{{ __('Cabinet') }}</a>
                                </li>
                            @endif
                        @else
                            @if (Route::has('showLoginForm'))
                                <li class="nav-item ml-3">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('showRegistrationForm'))
                                    <li class="nav-item ml-3">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign up free') }}</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main role="main" class="container py-4">
            @yield('content')
        </main>

        <footer class="footer mt-auto fixed-bottom">
            <div class="container">
                <span>&copy; {{ now()->year }} {{ Config::get('app.brand') }}</span>
            </div>
        </footer>
    </div>
@stack('scripts')

</body>
</html>
