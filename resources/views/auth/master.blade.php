<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="login-page theme-{{ lyra_theme() }}">
<head>
  <title>@yield('title', trans('lyra::theme.title') . " - " . trans('lyra::theme.description'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link rel="icon" type="image/png" href="/{{ config('lyra.routes.api.prefix') }}/assets/lyra-favicon">

  <link rel="stylesheet" href="/{{ config('lyra.routes.api.prefix') }}/styles/lyra">

</head>

<body>

<div>
  <div class="login-form">
    <div class="header">
      <div class="py-3">
        <img src="/{{ config('lyra.routes.api.prefix') }}/assets/lyra-logo" alt="Logo Lyra">
      </div>
    </div>

    <div class="content p-4">
      @yield('content')
    </div>
  </div>
</div>

</body>

</html>
