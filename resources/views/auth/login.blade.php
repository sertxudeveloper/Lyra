@extends('lyra::auth.master')

@section('title', config('lyra.admin.title') . " - " . config('lyra.admin.description'))

@section('content')
  <form method="POST" action="{{ route('lyra.login') }}">
    {{ csrf_field() }}
    <img src="{{ lyra_asset('images/lyra-logo.png') }}" alt="Logo Lyra">
    <h5>@lang('lyra::auth.welcome_message')</h5>

    <div class="form-login">
      <div class="form-group">
        <input type="email" id="inputEmail" class="form-control" name="email" required autofocus
               value="{{ old('email') }}" placeholder="@lang('lyra::fields.email')">
        <label class="form-control-placeholder" for="inputEmail">@lang('lyra::fields.email')</label>
        @if($errors->has('email'))
          {{ $errors->first('email') }}
        @endif
      </div>

      <div class="form-group">
        <input type="password" id="inputPassword" class="form-control" name="password" required
               placeholder="@lang('lyra::fields.password')">
        <label class="form-control-placeholder" for="inputPassword">@lang('lyra::fields.password')</label>
        @if($errors->has('password'))
          {{ $errors->first('password') }}
        @endif
      </div>
    </div>

    <div class="d-flex">
      <div class="checkbox mb-3 col-6 p-0">
        <label class="checkbox-container">@lang('lyra::auth.remember_me')
          <input type="checkbox" value="remember-me" name="remember">
          <span class="checkmark"></span>
        </label>
      </div>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">@lang('lyra::auth.login')</button>

    <div class="mt-5 mx-auto text-center">
      {!! trans('lyra::theme.footer_copyright', ['year' => date("Y")]) !!}
    </div>

  </form>
@endsection

