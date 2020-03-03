<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link rel="stylesheet" href="{{ lyra_asset('css/login.css') }}">
</head>

<body>

<div>
  <div class="login-form">
    <div class="header">
      <div class="py-3">
        <img src="{{ lyra_asset('images/lyra-logo.png') }}" alt="Logo Lyra">
      </div>
    </div>

    <div class="content p-4">
      @yield('content')
    </div>
  </div>
</div>

</body>

</html>
