<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if (Auth::user())
            <meta name="api-token" content="{{ Auth::user()->api_token }}">
        @endif


        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    </head>
    <body class="@yield('bodyClass')">
        <div id="app">
            <nav class="navbar navbar-light navbar-expand-lg bg-faded margin-bottom main">
                <div class="@yield('navbarClass', 'container')">
                    <a class="navbar-brand" href="/">
                        TravelJournal <i class="fa fa-plane"></i>
                    </a>
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
                    @else
                        <ul class="navbar-nav user-actions">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </nav>

            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        @yield('footer-scripts')
    </body>
</html>
