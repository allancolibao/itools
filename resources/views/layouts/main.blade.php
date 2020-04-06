<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#08090a">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'iTools') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
    <div class="logout">
        @guest
            <!-- Show nothing -->
            @else
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
            <a class="version" href="#">
                Version 1.0.0
            </a>
        </div>
        <div class="flex-center position-ref full-height">
            <div class="container">
                <h1 text-center>Welcome to iTools</h1>                
            </div>
        </div>

        <form method="POST" action="{{ action('MainController@search') }}" role="search" {{ Route::is('landing-page') ? 'hidden' : '' }} {{ Route::is('login') ? 'hidden' : '' }}>
            @csrf
            <div class="input-group">
                <input id="key" type="text" name="key" class="form-control" placeholder="Enter EACODE..." aria-label="key" aria-describedby="basic-addon2" autocomplete="off"  required autofocus>
                <button type="submit" name='submit' value='search' class="btn"><i class="fas fa-search fa-sm text-white-50"></i>Search</button>
            </div>
        </form>
        <main>
            @yield('content')
        </main>

        <footer class="footer">
            Nutritional Assessment and Monitoring Division
        </footer>
        <!-- Page level custom scripts -->
        <script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>
    </body>
</html>
