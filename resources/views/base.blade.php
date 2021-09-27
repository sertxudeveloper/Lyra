<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <meta name="lyra-api-route" content="{{ asset(config('lyra.routes.api.prefix')) }}">
  <meta name="lyra-web-route" content="{{ asset(config('lyra.routes.web.prefix')) }}">
  <title>Lyra Admin - {{ config('app.name') }}</title>
  <link rel="icon" type="image/png" href="{{ asset(config('lyra.routes.api.prefix').'/assets/images/favicon.png') }}"/>
  <link rel="stylesheet" href="{{ asset(config('lyra.routes.api.prefix').'/assets/css/app.css') }}">
</head>
<body class="h-screen">
<div id="app" class="flex h-full w-full bg-gray-200"></div>
<script src="{{ asset(config('lyra.routes.api.prefix').'/assets/js/app.js') }}"></script>
</body>
</html>
