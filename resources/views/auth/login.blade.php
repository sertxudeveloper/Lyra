@extends('lyra::auth.master')

@section('title', config('lyra.admin.title') . " - " . config('lyra.admin.description'))

@section('content')
  <form method="post">
    {{ csrf_field() }}

    <div class="form-group mt-2">
      <label for="email">@lang('lyra::fields.email')</label>
      <input type="email" class="form-control @if($errors->has('email'))is-invalid @endif" id="email" name="email" required autofocus
             value="{{ old('email') }}" placeholder="@lang('lyra::fields.email')">
      @if($errors->has('email')) <div class="invalid-feedback">{{ $errors->first('email') }}</div> @endif
    </div>

    <div class="form-group">
      <label for="password">@lang('lyra::fields.password')</label>
      <input type="password" class="form-control @if($errors->has('email'))is-invalid @endif" id="password" name="password" placeholder="@lang('lyra::fields.password')">
      @if($errors->has('password')) <div class="invalid-feedback">{{ $errors->first('password') }}</div> @endif
    </div>

    <div class="checkbox mb-5 col-6 p-0">
      <label class="checkbox-container">@lang('lyra::fields.remember_me')
        <input type="checkbox" value="remember-me" name="remember">
        <span class="checkmark"></span>
      </label>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-block btn-primary mx-auto w-75">@lang('lyra::fields.login')</button>
    </div>

  </form>
@endsection

