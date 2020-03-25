@extends('lyra::auth.master')

@section('content')
  <h4 class="mb-3 text-center">@lang('lyra::fields.reset_password')</h4>

  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <form method="post" action="{{ route('lyra.password.email') }}">
    @csrf

    <div class="form-group mt-2">
      <label for="email">@lang('lyra::fields.email')</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required
             autofocus value="{{ old('email') }}" placeholder="@lang('lyra::fields.email')">

      @error('email')
      <div class="invalid-feedback">{{ $errors->first('email') }}</div>
      @enderror
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-block btn-primary mx-auto w-75">
        @lang('lyra::fields.send_reset_link')
      </button>
    </div>

  </form>
@endsection

