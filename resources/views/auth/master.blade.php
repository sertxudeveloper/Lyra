<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link rel="stylesheet" href="{{ lyra_asset('css/login.css') }}">
</head>

<body class="form-body" style="background-image:url({{ lyra_asset('images/background-stars.png') }})">

<div>
  <div class="form-container">
    @yield('content')
  </div>
</div>

<div class="overlay"></div>

</body>
</html>
