<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Lyra Admin - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('lyra.routes.api.prefix').'/assets/images/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ asset(config('lyra.routes.api.prefix').'/assets/css/app.css') }}">
</head>
<body class="bg-gray-200">
    <div id="app" v-cloak class="flex flex-grow-0 flex-shrink-0 h-screen w-screen">
        @include('lyra::partials.sidebar')
        @include('lyra::partials.main')
        <notification-wrapper ref="notification-wrapper"></notification-wrapper>
    </div>
    <script>
        window.config = {
          base: '/{{ config('lyra.routes.web.prefix') }}',
          apiRoute: '{{ asset(config('lyra.routes.api.prefix')) }}',
          lang: '{{ app()->getLocale() }}',
        }
    </script>
    <script src="{{ asset(config('lyra.routes.api.prefix').'/assets/js/app.js') }}"></script>
</body>
</html>
