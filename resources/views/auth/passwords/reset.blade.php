@extends('lyra::auth.master')

@section('content')
  <h4 class="mb-3 text-center">@lang('lyra::fields.reset_password')</h4>

  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <form method="post" action="{{ route('lyra.password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group mt-2">
      <label for="email">@lang('lyra::fields.email')</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required
             autofocus value="{{ old('email') }}" placeholder="@lang('lyra::fields.email')">

      @error('email')
      <div class="invalid-feedback">{{ $errors->first('email') }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="password">@lang('lyra::fields.password')</label>
      <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
             placeholder="@lang('lyra::fields.password')" required>

      @error('password')
      <div class="invalid-feedback">{{ $errors->first('password') }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="password-confirm">@lang('lyra::fields.password_confirm')</label>
      <input type="password" class="form-control" id="password-confirm" name="password-confirm"
             placeholder="@lang('lyra::fields.password_confirm')" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-block btn-primary mx-auto w-75">
        @lang('lyra::fields.reset_password')
      </button>
    </div>

  </form>
@endsection

