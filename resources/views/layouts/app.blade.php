<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="{{ asset('images/favicon.png') }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if (Auth::user())
            <meta name="api-token" content="{{ Auth::user()->api_token }}">
        @endif

        <title>@yield('title') - WanderlustScrapbook</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body class="@yield('bodyClass')">
        <div id="app">
            <nav class="navbar navbar-light navbar-expand-lg bg-faded margin-bottom main">
                <div class="@yield('navbarClass', 'container')">
                    <div class="navbar-content">
                        <a class="navbar-brand" href="/" id="logo">
                            <img src="{{ asset('images/logo.png') }}" width="230px">
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                        @if(Auth::user())
                            <ul class="navbar-nav justify-content-end">
                                <li class="nav-item {{ Request::path() == 'trips' ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('trip.index') }}">My Trips</a>
                                </li>
                                <li class="nav-item {{ Request::path() == 'trip/create' ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('trip.create') }}">Add a Trip</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-link">
                                    <form method="post" action="{{ route('logout') }}">
                                        {{ csrf_field() }}
                                        <button id="logout">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        @else
                            <ul class="navbar-nav user-actions justify-content-end">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </nav>

            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('footer-scripts')
    </body>
</html>
