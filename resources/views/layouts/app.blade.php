<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    </head>
    <body class="@yield('bodyClass')">
        <div id="app">
            <nav class="navbar navbar-light navbar-expand-lg bg-faded margin-bottom main">
                <div class="@yield('navbarClass', 'container')">
                    <a class="navbar-brand">Logo</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            @if(Auth::user())
                                <li class="nav-item {{ Request::path() == 'trips' ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('trip.index') }}">My Trips</a>
                                </li>
                                <li class="nav-item {{ Request::path() == 'trip/create' ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('trip.create') }}">Add a Trip</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @if(Auth::user())
                        <form method="post" action="{{ route('logout') }}">
                            {{ csrf_field() }}
                            <button id="logout">Logout</button>
                        </form>
                    @endif
                </div>
            </nav>

            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('footer-scripts')
    </body>
</html>
