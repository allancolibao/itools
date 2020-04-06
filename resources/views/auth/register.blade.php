<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'iTools') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <h1>404 | Not found</h1>
    <h6><a href="{{route('home')}}">Go to home</a></h6>
</body>
</html>