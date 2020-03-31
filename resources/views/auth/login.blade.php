@extends('lyra::auth.master')

@section('content')
  <form method="post">
    @csrf

    <div class="form-group mt-2">
      <label for="email">@lang('lyra::fields.email')</label>
      <input type="email" class="form-control @if($errors->has('email'))is-invalid @endif" autofocus
             id="email" name="email" required value="{{ old('email') }}" placeholder="@lang('lyra::fields.email')">

      @error('email')
      <div class="invalid-feedback">{{ $errors->first('email') }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="password">@lang('lyra::fields.password')</label>
      <input type="password" class="form-control @if($errors->has('email'))is-invalid @endif"
             id="password" name="password" required placeholder="@lang('lyra::fields.password')">

      @error('password')
      <div class="invalid-feedback">{{ $errors->first('password') }}</div>
      @enderror
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

  @if(config('lyra.authenticator') === \SertxuDeveloper\Lyra\Lyra::MODE_ADVANCED)
    <hr>
    <div class="text-center">
      <a href="{{ route("lyra.password.request") }}">Forgot your password?</a>
    </div>
  @endif
@endsection

