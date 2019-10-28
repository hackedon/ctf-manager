<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{asset('img/favicon.png')}}">
    <script type="text/javascript" src="{{asset('js/countdown.js')}}"></script>

    @toastr_css

    <style type="text/css">
        .countdown {
            display: flex;
        }

        .countdown .day,
        .countdown .hour,
        .countdown .min,
        .countdown .sec {
            color: #297ed8;
            padding: 1vw 3vw;
            text-align: center;
        }

        .countdown .day .num,
        .countdown .hour .num,
        .countdown .min .num,
        .countdown .sec .num {
            display: block;
            font-size: 4vw;
            line-height: 1em;
        }

        .countdown .day .word,
        .countdown .hour .word,
        .countdown .min .word,
        .countdown .sec .word {
            display: block;
            font-size: 1vw;
            color: #8a99ab;
        }
    </style>

</head>
<body style="background: #343a40">
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark shadow" style="background: rgb(32, 37, 41)">
        <div class="container">
            <a class="navbar-brand p-0" href="{{ url('/') }}">
                <img src="{{asset('img/logo2.png')}}" style="width: 100px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link mr-3" href="{{route('admin.summary')}}">Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item mr-3">
                            <a class="nav-link" href="{{route('admin.summary')}}">Summary</a>
                        </li>
                        <li class="nav-item mr-3">
                            <a class="nav-link" href="{{route('rules')}}">Rules</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->display_name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{route('admin.home')}}" class="dropdown-item">Admin</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
<script>
    feather.replace()
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.bundle.min.js"></script>
@jquery
@toastr_js
@toastr_render
</html>
