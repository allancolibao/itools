<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>iTools</title>

        <!-- Page level custom style -->
        <link href="{{asset('css/style.css')}}" rel="stylesheet">

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="container">
                <h1 text-center>Welcome to iTools</h1>
                <form method="POST" action="{{ action('MainController@search') }}" role="search">
                    @csrf
                    <div class="input-group">
                    <input id="key" type="text" name="key" class="form-control" placeholder="Enter EACODE..." aria-label="key" aria-describedby="basic-addon2"  required autofocus>
                    <button type="submit" name='submit' value='search' class="btn"><i class="fas fa-search fa-sm text-white-50"></i>Search</button>
                    </div>
                </form>
            </div>
        </div>

        @include('inc.message')

        <main>
            @yield('content')
        </main>

        <!-- Page level custom scripts -->
        <script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>
    </body>
</html>
